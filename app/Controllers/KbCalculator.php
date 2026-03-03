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
        // AMBIL 12 BULAN TERAKHIR (1 TAHUN) UNTUK KALKULASI YANG LEBIH AKURAT
        $periods = $this->periodModel->orderBy('start_date', 'DESC')->findAll(12);

        $conclusion = [
            'status' => 'Belum Cukup Data',
            'message' => 'Silakan catat minimal 2 siklus haid. (Saran: 6 siklus untuk hasil akurat)',
            'safe_start' => '-',
            'safe_end' => '-',
            'next_period_start' => '-',
            'next_period_end' => '-',
            'late_date' => '-',
            'color_class' => 'bg-secondary'
        ];

        $countPeriods = count($periods);

        if ($countPeriods >= 2) {
            $cycles = [];
            for ($i = 0; $i < $countPeriods - 1; $i++) {
                $current = Time::parse($periods[$i]['start_date']);
                $prev    = Time::parse($periods[$i + 1]['start_date']);
                $diff    = $prev->difference($current)->getDays();
                $cycles[] = $diff;
            }

            if (!empty($cycles)) {
                $minCycle = min($cycles);
                $maxCycle = max($cycles);

                $firstFertileDay = $minCycle - 18;
                $lastFertileDay  = $maxCycle - 11;

                $lastPeriodStart = Time::parse($periods[0]['start_date']);

                $fertileWindowStart = Time::parse($periods[0]['start_date'])->addDays($firstFertileDay);
                $fertileWindowEnd   = Time::parse($periods[0]['start_date'])->addDays($lastFertileDay);

                $nextPeriodStartObj = Time::parse($periods[0]['start_date'])->addDays($minCycle);
                $nextPeriodEndObj   = Time::parse($periods[0]['start_date'])->addDays($maxCycle);
                $lateDateObj        = Time::parse($periods[0]['start_date'])->addDays($maxCycle + 1);

                $conclusion['next_period_start'] = $nextPeriodStartObj->toLocalizedString('d MMM YYYY');
                $conclusion['next_period_end']   = $nextPeriodEndObj->toLocalizedString('d MMM YYYY');
                $conclusion['late_date']         = $lateDateObj->toLocalizedString('d MMM YYYY');

                $today = Time::now();

                // Warning jika data kurang dari 6 bulan
                $accuracyWarning = ($countPeriods < 6) ? " (Akurasi rendah: Catat min. 6 bulan untuk hasil optimal)" : "";

                if ($today >= $fertileWindowStart && $today <= $fertileWindowEnd) {
                    $conclusion['status'] = 'BAHAYA (Masa Subur)';
                    $conclusion['message'] = 'Jangan berhubungan tanpa pengaman hari ini!' . $accuracyWarning;
                    $conclusion['color_class'] = 'bg-danger text-white';
                } elseif ($today < $fertileWindowStart) {
                    $diff = $today->difference($fertileWindowStart)->getDays();
                    if ($diff <= 2) {
                        $conclusion['status'] = 'HATI-HATI';
                        $conclusion['message'] = 'Masa subur akan dimulai dalam 2 hari.' . $accuracyWarning;
                        $conclusion['color_class'] = 'bg-warning text-dark';
                    } else {
                        $conclusion['status'] = 'AMAN';
                        $conclusion['message'] = 'Hari ini kemungkinan besar aman.' . $accuracyWarning;
                        $conclusion['color_class'] = 'bg-success text-white';
                    }
                } else {
                    if ($today >= $lateDateObj) {
                        $conclusion['status'] = 'TELAT HAID';
                        $conclusion['message'] = 'Masa subur telah lewat, Anda sudah telat haid. Segera lakukan test pack.' . $accuracyWarning;
                        $conclusion['color_class'] = 'bg-info text-white';
                    } else {
                        $conclusion['status'] = 'SANGAT AMAN';
                        $conclusion['message'] = 'Masa subur telah lewat.' . $accuracyWarning;
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
        // LOGIKA DELETE DIHAPUS. Kita biarkan data tersimpan sebagai riwayat medis jangka panjang.

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
