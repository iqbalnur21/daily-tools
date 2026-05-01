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
    // Body JSON: { title, notes, rating, seasons: [{season_num, total_eps}], watched_season, watched_episode }
    // -------------------------------------------------------
    public function store()
    {
        $input = $this->request->getJSON(true);

        // Validasi minimal
        if (empty($input['title'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Judul wajib diisi']);
        }

        // Simpan series, bypass type (ditetapkan ke default 'series')
        $seriesId = $this->series->insert([
            'title'  => trim($input['title']),
            'type'   => 'series',
            'notes'  => $input['notes']  ?? null,
            'rating' => isset($input['rating']) && $input['rating'] !== '' ? (int)$input['rating'] : null,
        ]);

        // Tangkap input default progress tontonan
        $watchedSeason  = !empty($input['watched_season']) ? (int)$input['watched_season'] : 1;
        $watchedEpisode = !empty($input['watched_episode']) ? (int)$input['watched_episode'] : 1;

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

                // Generate episode rows, apply logic progress default
                for ($ep = 1; $ep <= $totalEps; $ep++) {
                    $status = 0;
                    // Jika season ini < watched_season ATAU di season yang sama dan ep <= watched_episode
                    if ($seasonNum < $watchedSeason || ($seasonNum == $watchedSeason && $ep <= $watchedEpisode)) {
                        $status = 2; // Otomatis sudah ditonton
                    }

                    $this->episodes->insert([
                        'season_id'  => $seasonId,
                        'ep_num'     => $ep,
                        'status'     => $status,
                        'watched_at' => ($status === 2) ? date('Y-m-d H:i:s') : null,
                    ]);
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'id' => $seriesId]);
    }

    // -------------------------------------------------------
    // PUT /series/update/{id}
    // Update data series (tanpa merubah type) & rebuild episode dengan retain progress sebelumnya
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
            'notes'  => $input['notes']  ?? $series['notes'],
            'rating' => isset($input['rating']) && $input['rating'] !== '' ? (int)$input['rating'] : null,
        ]);

        // Jika seasons dikirim ulang, rebuild
        if (isset($input['seasons']) && is_array($input['seasons'])) {
            // Hapus seasons lama (episodes terhapus otomatis jika FK CASCADE DB aktif)
            $oldSeasons = $this->seasons->where('series_id', $id)->findAll();
            foreach ($oldSeasons as $os) {
                $this->seasons->delete($os['id']);
            }

            // Buat seasons baru
            foreach ($input['seasons'] as $s) {
                $seasonNum = (int)($s['season_num'] ?? 1);
                $totalEps  = (int)($s['total_eps']  ?? 1);

                // Tarik data progress lama yang di bypass ke JSON
                $existingEps = $s['episodes'] ?? [];

                $seasonId = $this->seasons->insert([
                    'series_id'  => $id,
                    'season_num' => $seasonNum,
                    'total_eps'  => $totalEps,
                ]);

                for ($ep = 1; $ep <= $totalEps; $ep++) {
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

        $this->series->delete($id);

        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------
    // POST /series/disable/{id}
    // Mengubah status kolom disabled di DB menjadi 1 (Hide UI)
    // -------------------------------------------------------
    public function disable($id)
    {
        $series = $this->series->find($id);
        if (!$series) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->series->update($id, ['disabled' => 1]);

        return $this->response->setJSON(['success' => true]);
    }

    // -------------------------------------------------------
    // POST /series/episodes/batch-update
    // Memproses payload update debounce ajax.
    // JSON: { updates: { 'episode_id': 2, 'episode_id': 0 } }
    // -------------------------------------------------------
    public function batchUpdateEpisodes()
    {
        $input = $this->request->getJSON(true);
        $updates = $input['updates'] ?? [];

        if (empty($updates)) {
            return $this->response->setJSON(['success' => true]);
        }

        foreach ($updates as $epId => $status) {
            $this->episodes->update($epId, [
                'status'     => (int)$status,
                'watched_at' => ((int)$status === 2) ? date('Y-m-d H:i:s') : null,
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    } // -------------------------------------------------------
    // GET /series/list-disabled
    // Mengambil semua series yang status disabled = 1
    // -------------------------------------------------------
    public function listDisabled()
    {
        $seriesList = $this->series->where('disabled', 1)->orderBy('title', 'ASC')->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $seriesList]);
    }

    // -------------------------------------------------------
    // POST /series/restore/{id}
    // Mengembalikan status disabled menjadi 0 agar tampil di list utama
    // -------------------------------------------------------
    public function restore($id)
    {
        $series = $this->series->find($id);
        if (!$series) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        // Set disabled kembali ke 0 (aktif). 
        // Ubah menjadi 2 jika sistem kamu mewajibkan angka 2.
        $this->series->update($id, ['disabled' => 0]);

        return $this->response->setJSON(['success' => true]);
    }
}
