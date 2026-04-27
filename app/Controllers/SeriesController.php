<?php

namespace App\Controllers;

use App\Models\SeriesTracker;
use App\Models\SeriesSeasons;
use App\Models\SeriesEpisodes;

class SeriesController extends BaseController
{
    protected $series;
    protected $seasons;
    protected $episodes;

    public function __construct()
    {
        $this->series   = new SeriesTracker();
        $this->seasons  = new SeriesSeasons();
        $this->episodes = new SeriesEpisodes();
    }

    // -------------------------------------------------------
    // GET /series/list
    // Kembalikan semua series (tidak disabled) beserta seasons & episodes
    // -------------------------------------------------------
    public function list()
    {
        $seriesList = $this->series->where('disabled', 0)->orderBy('title', 'ASC')->findAll();

        foreach ($seriesList as &$s) {
            $seasons = $this->seasons
                ->where('series_id', $s['id'])
                ->orderBy('season_num', 'ASC')
                ->findAll();

            foreach ($seasons as &$season) {
                $season['episodes'] = $this->episodes
                    ->where('season_id', $season['id'])
                    ->orderBy('ep_num', 'ASC')
                    ->findAll();
            }

            $s['seasons'] = $seasons;
        }

        return $this->response->setJSON(['success' => true, 'data' => $seriesList]);
    }

    // -------------------------------------------------------
    // POST /series/store
    // Body JSON: { title, type, notes, rating, seasons: [{season_num, total_eps}] }
    // -------------------------------------------------------
    public function store()
    {
        $input = $this->request->getJSON(true);

        // Validasi minimal
        if (empty($input['title'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Judul wajib diisi']);
        }

        // Simpan series
        $seriesId = $this->series->insert([
            'title'  => trim($input['title']),
            'type'   => $input['type']   ?? 'series',
            'notes'  => $input['notes']  ?? null,
            'rating' => isset($input['rating']) && $input['rating'] !== '' ? (int)$input['rating'] : null,
        ]);

        // Simpan seasons + generate episode rows
        if (!empty($input['seasons']) && is_array($input['seasons'])) {
            foreach ($input['seasons'] as $s) {
                $seasonNum = (int)($s['season_num'] ?? 1);
                $totalEps  = (int)($s['total_eps']  ?? 1);

                $seasonId = $this->seasons->insert([
                    'series_id'  => $seriesId,
                    'season_num' => $seasonNum,
                    'total_eps'  => $totalEps,
                ]);

                // Generate episode rows
                for ($ep = 1; $ep <= $totalEps; $ep++) {
                    $this->episodes->insert([
                        'season_id' => $seasonId,
                        'ep_num'    => $ep,
                        'status'    => 0,
                    ]);
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'id' => $seriesId]);
    }

    // -------------------------------------------------------
    // PUT /series/update/{id}
    // Bisa update title/type/notes/rating dan seasons
    // Jika seasons dikirim, hapus seasons lama lalu buat ulang
    // -------------------------------------------------------
    public function update($id)
    {
        $input = $this->request->getJSON(true);

        $series = $this->series->find($id);
        if (!$series) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        // Update field series
        $this->series->update($id, [
            'title'  => trim($input['title']  ?? $series['title']),
            'type'   => $input['type']   ?? $series['type'],
            'notes'  => $input['notes']  ?? $series['notes'],
            'rating' => isset($input['rating']) && $input['rating'] !== '' ? (int)$input['rating'] : null,
        ]);

        // Jika seasons dikirim ulang, rebuild
        if (isset($input['seasons']) && is_array($input['seasons'])) {
            // Hapus seasons lama (episodes terhapus via CASCADE)
            $oldSeasons = $this->seasons->where('series_id', $id)->findAll();
            foreach ($oldSeasons as $os) {
                $this->seasons->delete($os['id']);
            }

            // Buat seasons baru
            foreach ($input['seasons'] as $s) {
                $seasonNum = (int)($s['season_num'] ?? 1);
                $totalEps  = (int)($s['total_eps']  ?? 1);

                // Kalau season sudah punya data episode (dikirim dari client), restore statusnya
                $existingEps = $s['episodes'] ?? [];

                $seasonId = $this->seasons->insert([
                    'series_id'  => $id,
                    'season_num' => $seasonNum,
                    'total_eps'  => $totalEps,
                ]);

                for ($ep = 1; $ep <= $totalEps; $ep++) {
                    // Cari status lama jika ada
                    $oldStatus = 0;
                    foreach ($existingEps as $oe) {
                        if ((int)$oe['ep_num'] === $ep) {
                            $oldStatus = (int)$oe['status'];
                            break;
                        }
                    }

                    $this->episodes->insert([
                        'season_id'  => $seasonId,
                        'ep_num'     => $ep,
                        'status'     => $oldStatus,
                        'watched_at' => ($oldStatus === 2) ? date('Y-m-d H:i:s') : null,
                    ]);
                }
            }
        }

        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------
    // DELETE /series/delete/{id}
    // -------------------------------------------------------
    public function delete($id)
    {
        $series = $this->series->find($id);
        if (!$series) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        // Cascade akan hapus seasons & episodes otomatis (jika FK CASCADE aktif)
        $this->series->delete($id);

        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------
    // POST /series/episode/toggle
    // Body JSON: { episode_id }
    // Siklus status: 0 -> 1 (orange/watching) -> 2 (hijau/done) -> 0 (belum)
    // -------------------------------------------------------
    public function toggleEpisode()
    {
        $input = $this->request->getJSON(true);
        $epId  = (int)($input['episode_id'] ?? 0);

        $ep = $this->episodes->find($epId);
        if (!$ep) {
            return $this->response->setJSON(['success' => false, 'message' => 'Episode tidak ditemukan']);
        }

        $currentStatus = (int)$ep['status'];
        // Siklus: 0 -> 1 -> 2 -> 0
        $newStatus = ($currentStatus + 1) % 3;

        $this->episodes->update($epId, [
            'status'     => $newStatus,
            'watched_at' => ($newStatus === 2) ? date('Y-m-d H:i:s') : $ep['watched_at'],
        ]);

        return $this->response->setJSON([
            'success'    => true,
            'episode_id' => $epId,
            'new_status' => $newStatus,
        ]);
    }
}
