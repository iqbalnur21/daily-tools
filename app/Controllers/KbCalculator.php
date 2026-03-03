<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
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

        // --- LOGIC HITUNG KB & HAID ---
        $conclusion = [
            'status' => 'warning', // default
            'message' => 'Data belum cukup untuk prediksi (Minimal 2 siklus)',
            'safe_start' => '-',
            'safe_end' => '-',
            'next_period_start' => '-', // Tambahan: Perkiraan awal haid
            'next_period_end' => '-',   // Tambahan: Perkiraan akhir haid
            'late_date' => '-',         // Tambahan: Terhitung telat
            'color_class' => 'bg-secondary'
        ];

        if (count($periods) >= 2) {
            $cycles = [];
            // Hitung panjang siklus antar bulan
            for ($i = 0; $i < count($periods) - 1; $i++) {
                $current = Time::parse($periods[$i]['start_date']);
                $prev    = Time::parse($periods[$i + 1]['start_date']);
                $diff    = $prev->difference($current)->getDays();
                $cycles[] = $diff;
            }

            if (!empty($cycles)) {
                $minCycle = min($cycles);
                $maxCycle = max($cycles);

                // Rumus KB Kalender
                $firstFertileDay = $minCycle - 18;
                $lastFertileDay  = $maxCycle - 11;

                // Patokan perhitungan adalah hari pertama haid TERAKHIR
                $lastPeriodStart = Time::parse($periods[0]['start_date']);

                $fertileWindowStart = Time::parse($periods[0]['start_date'])->addDays($firstFertileDay);
                $fertileWindowEnd   = Time::parse($periods[0]['start_date'])->addDays($lastFertileDay);

                // --- PERHITUNGAN HAID SELANJUTNYA ---
                $nextPeriodStartObj = Time::parse($periods[0]['start_date'])->addDays($minCycle);
                $nextPeriodEndObj   = Time::parse($periods[0]['start_date'])->addDays($maxCycle);
                $lateDateObj        = Time::parse($periods[0]['start_date'])->addDays($maxCycle + 1);

                $conclusion['next_period_start'] = $nextPeriodStartObj->toLocalizedString('d MMM YYYY');
                $conclusion['next_period_end']   = $nextPeriodEndObj->toLocalizedString('d MMM YYYY');
                $conclusion['late_date']         = $lateDateObj->toLocalizedString('d MMM YYYY');

                $today = Time::now();

                // Cek Status Hari Ini
                if ($today >= $fertileWindowStart && $today <= $fertileWindowEnd) {
                    $conclusion['status'] = 'BAHAYA (Masa Subur)';
                    $conclusion['message'] = 'Jangan berhubungan tanpa pengaman hari ini!';
                    $conclusion['color_class'] = 'bg-danger text-white';
                } elseif ($today < $fertileWindowStart) {
                    $diff = $today->difference($fertileWindowStart)->getDays();
                    if ($diff <= 2) {
                        $conclusion['status'] = 'HATI-HATI';
                        $conclusion['message'] = 'Masa subur akan dimulai dalam 2 hari.';
                        $conclusion['color_class'] = 'bg-warning text-dark'; // Ubah text jadi dark agar terbaca di kuning
                    } else {
                        $conclusion['status'] = 'AMAN';
                        $conclusion['message'] = 'Hari ini kemungkinan besar aman.';
                        $conclusion['color_class'] = 'bg-success text-white';
                    }
                } else {
                    // Pengecekan tambahan jika hari ini sudah masuk tanggal telat haid
                    if ($today >= $lateDateObj) {
                        $conclusion['status'] = 'TELAT HAID';
                        $conclusion['message'] = 'Masa subur telah lewat, namun Anda sudah telat haid. Segera lakukan test pack jika diperlukan.';
                        $conclusion['color_class'] = 'bg-info text-white';
                    } else {
                        $conclusion['status'] = 'SANGAT AMAN';
                        $conclusion['message'] = 'Masa subur telah lewat.';
                        $conclusion['color_class'] = 'bg-success text-white';
                    }
                }

                $conclusion['safe_start'] = $fertileWindowStart->toLocalizedString('d MMM YYYY');
                $conclusion['safe_end']   = $fertileWindowEnd->toLocalizedString('d MMM YYYY');
            }
        }

        $data = [
            'assetsPath' => base_url(),
            'periods'    => $periods,
            'conclusion' => $conclusion
        ];

        return view('kb_dashboard', $data);
    }

    public function store()
    {
        $count = $this->periodModel->countAllResults();

        if ($count >= 6) {
            $oldest = $this->periodModel->orderBy('start_date', 'ASC')->first();
            if ($oldest) {
                $this->periodModel->delete($oldest['id']);
            }
        }

        $this->periodModel->save([
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
        ]);

        return redirect()->to('/KbCalculator')->with('success', 'Data berhasil ditambahkan');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->periodModel->save([
            'id'         => $id,
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
        ]);

        return redirect()->to('/KbCalculator')->with('success', 'Data berhasil diubah');
    }
}
