<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeriodModel; // Pastikan buat Model dulu (standar CI4)
use CodeIgniter\I18n\Time;

class KbCalculator extends BaseController
{
    protected $periodModel;

    public function __construct()
    {
        $this->periodModel = new PeriodModel();
    }

    public function index()
    {
        // Ambil data urut dari tanggal terbaru
        $periods = $this->periodModel->orderBy('start_date', 'DESC')->findAll(6);
        
        // --- LOGIC HITUNG KB (Metode Ogino-Knaus / Kalender) ---
        $conclusion = [
            'status' => 'warning', // default
            'message' => 'Data belum cukup untuk prediksi (Minimal 2 siklus)',
            'safe_start' => '-',
            'safe_end' => '-',
            'color_class' => 'bg-secondary'
        ];

        if (count($periods) >= 2) {
            $cycles = [];
            // Hitung panjang siklus antar bulan
            // Array $periods index 0 adalah yang paling baru (bulan ini/lalu)
            for ($i = 0; $i < count($periods) - 1; $i++) {
                $current = Time::parse($periods[$i]['start_date']);
                $prev    = Time::parse($periods[$i+1]['start_date']);
                $diff    = $prev->difference($current)->getDays();
                $cycles[] = $diff;
            }

            if (!empty($cycles)) {
                $minCycle = min($cycles);
                $maxCycle = max($cycles);

                // Rumus KB Kalender
                // Masa subur awal = Siklus Terpendek - 18
                // Masa subur akhir = Siklus Terpanjang - 11
                $firstFertileDay = $minCycle - 18;
                $lastFertileDay  = $maxCycle - 11;

                // Patokan perhitungan adalah hari pertama haid TERAKHIR
                $lastPeriodStart = Time::parse($periods[0]['start_date']);
                
                $fertileWindowStart = $lastPeriodStart->addDays($firstFertileDay);
                $fertileWindowEnd   = $lastPeriodStart->addDays($lastFertileDay);

                $today = Time::now();

                // Cek Status Hari Ini
                if ($today >= $fertileWindowStart && $today <= $fertileWindowEnd) {
                    $conclusion['status'] = 'BAHAYA (Masa Subur)';
                    $conclusion['message'] = 'Jangan berhubungan tanpa pengaman hari ini!';
                    $conclusion['color_class'] = 'bg-danger text-white';
                } elseif ($today < $fertileWindowStart) {
                     // Belum masuk masa subur (Fase Folikuler) - Relatif Aman tapi hati-hati jelang subur
                    $diff = $today->difference($fertileWindowStart)->getDays();
                    if($diff <= 2) {
                        $conclusion['status'] = 'HATI-HATI';
                        $conclusion['message'] = 'Masa subur akan dimulai dalam 2 hari.';
                        $conclusion['color_class'] = 'bg-warning text-white';
                    } else {
                        $conclusion['status'] = 'AMAN';
                        $conclusion['message'] = 'Hari ini kemungkinan besar aman.';
                        $conclusion['color_class'] = 'bg-success text-white';
                    }
                } else {
                    // Lewat masa subur (Fase Luteal) - Paling Aman
                    $conclusion['status'] = 'SANGAT AMAN';
                    $conclusion['message'] = 'Masa subur telah lewat.';
                    $conclusion['color_class'] = 'bg-success text-white';
                }

                $conclusion['safe_start'] = $fertileWindowStart->toLocalizedString('d MMM YYYY');
                $conclusion['safe_end']   = $fertileWindowEnd->toLocalizedString('d MMM YYYY');
            }
        }

        $data = [
            'assetsPath' => base_url(), // Asumsi assets ada di public
            'periods'    => $periods,
            'conclusion' => $conclusion
        ];

        return view('kb_dashboard', $data);
    }

    public function store()
    {
        // Rules: Jika data > 6, hapus yang terlama (paling kecil tanggalnya)
        $count = $this->periodModel->countAllResults();
        
        if ($count >= 6) {
            // Cari ID terlama
            $oldest = $this->periodModel->orderBy('start_date', 'ASC')->first();
            if ($oldest) {
                $this->periodModel->delete($oldest['id']);
            }
        }

        $this->periodModel->save([
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
        ]);

        return redirect()->to('/')->with('success', 'Data berhasil ditambahkan');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->periodModel->save([
            'id'         => $id,
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
        ]);

        return redirect()->to('/')->with('success', 'Data berhasil diubah');
    }
}