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
        $periods = $this->periodModel->orderBy('start_date', 'DESC')->findAll(12);

        // Hitung panjang siklus antar haid dan simpan ke dalam array $periods
        $today = Time::now();
        for ($i = 0; $i < count($periods); $i++) {
            $start = Time::parse($periods[$i]['start_date']);

            if ($i === 0) {
                // Siklus terakhir/terbaru: Hitung dari tanggal mulai sampai HARI INI
                $periods[$i]['cycle_length'] = abs($start->difference($today)->getDays());
                $periods[$i]['is_current_cycle'] = true;
            } else {
                // Siklus sebelumnya: Hitung dari tanggal mulai saat ini ke tanggal mulai haid berikutnya (index sebelumnya)
                $newerStart = Time::parse($periods[$i - 1]['start_date']);
                $periods[$i]['cycle_length'] = abs($start->difference($newerStart)->getDays());
                $periods[$i]['is_current_cycle'] = false;
            }
        }

        // Cek apakah ada haid yang sedang berlangsung (end_date kosong/null)
        $is_ongoing = false;
        $active_id = null;

        if (!empty($periods) && empty($periods[0]['end_date'])) {
            $is_ongoing = true;
            $active_id = $periods[0]['id'];
        }

        $conclusion = [
            'status' => 'Belum Cukup Data',
            'message' => 'Silakan catat minimal 2 siklus haid.',
            'safe_start' => 'Pending',
            'safe_end' => 'Pending',
            'next_period_start' => 'Pending',
            'next_period_end' => 'Pending',
            'late_date' => 'Pending',
            'color_class' => 'bg-secondary'
        ];

        $countPeriods = count($periods);

        // LOGIKA JIKA SEDANG HAID
        if ($is_ongoing) {
            $conclusion['status'] = 'SEDANG HAID';
            $conclusion['message'] = 'Silakan input tanggal selesai haid untuk melihat prediksi masa subur Anda.';
            $conclusion['color_class'] = 'bg-info text-white';
        }
        // LOGIKA NORMAL JIKA TIDAK SEDANG HAID DAN DATA >= 2
        elseif ($countPeriods >= 2) {
            $cycles = [];
            // Hitung hanya yang sudah memiliki end_date
            for ($i = 0; $i < $countPeriods - 1; $i++) {
                if (!empty($periods[$i]['end_date']) && !empty($periods[$i + 1]['end_date'])) {
                    $current = Time::parse($periods[$i]['start_date']);
                    $prev    = Time::parse($periods[$i + 1]['start_date']);
                    $diff    = $prev->difference($current)->getDays();
                    $cycles[] = $diff;
                }
            }

            if (!empty($cycles)) {
                $minCycle = min($cycles);
                $maxCycle = max($cycles);

                $firstFertileDay = $minCycle - 18;
                $lastFertileDay  = $maxCycle - 11;

                $fertileWindowStart = Time::parse($periods[0]['start_date'])->addDays($firstFertileDay);
                $fertileWindowEnd   = Time::parse($periods[0]['start_date'])->addDays($lastFertileDay);

                $nextPeriodStartObj = Time::parse($periods[0]['start_date'])->addDays($minCycle);
                $nextPeriodEndObj   = Time::parse($periods[0]['start_date'])->addDays($maxCycle);
                $lateDateObj        = Time::parse($periods[0]['start_date'])->addDays($maxCycle + 1);

                $conclusion['next_period_start'] = $nextPeriodStartObj->toLocalizedString('d MMM YYYY');
                $conclusion['next_period_end']   = $nextPeriodEndObj->toLocalizedString('d MMM YYYY');
                $conclusion['late_date']         = $lateDateObj->toLocalizedString('d MMM YYYY');

                $today = Time::now();
                $accuracyWarning = ($countPeriods < 6) ? " (Akurasi rendah: Catat min. 6 bulan)" : "";

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
                        $conclusion['message'] = 'Masa subur telah lewat, Anda sudah telat haid.' . $accuracyWarning;
                        $conclusion['color_class'] = 'bg-warning text-dark';
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
            'conclusion' => $conclusion,
            'is_ongoing' => $is_ongoing,
            'active_id'  => $active_id
        ];

        return view('kb_dashboard', $data);
    }

    public function storeStart()
    {
        $this->periodModel->save([
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => null,
        ]);
        return redirect()->to('/KbCalculator')->with('success', 'Mulai haid berhasil dicatat');
    }

    public function storeEnd()
    {
        $this->periodModel->save([
            'id'         => $this->request->getPost('id'),
            'end_date'   => $this->request->getPost('end_date'),
        ]);
        return redirect()->to('/KbCalculator')->with('success', 'Selesai haid berhasil dicatat');
    }

    public function update()
    {
        $this->periodModel->save([
            'id'         => $this->request->getPost('id'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => empty($this->request->getPost('end_date')) ? null : $this->request->getPost('end_date'),
        ]);
        return redirect()->to('/KbCalculator')->with('success', 'Data berhasil diubah');
    }
}
