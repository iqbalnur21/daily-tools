<?php

namespace App\Controllers;

use \App\Models\Counters;

class Home extends BaseController
{
    protected $counters;
    public function __construct()
    {
        $this->counters = new Counters();
    }
    public function index()
    {
        helper('custom');

        $data['counters'] = $this->counters->findAll();
        $data['assetsPath'] = (strpos(current_url(), 'balrafa.tech') !== false) ? env('app.assetsPath') : base_url();
        return view('home', $data);
    }
    public function update()
    {
        parse_str($this->request->getBody(), $data);

        // Collect all counter IDs from the request
        $counterIds = [];
        foreach ($data as $key => $value) {
            $counterIds[] = str_replace('counter-', '', $key);
        }

        // Fetch current DB data for the relevant counters
        $existingCounters = $this->counters
            ->whereIn('counter_id', $counterIds)
            ->findAll();

        // Index by counter_id for easy lookup
        $existingMap = [];
        foreach ($existingCounters as $counter) {
            $existingMap[$counter['counter_id']] = $counter['amount'];
        }

        $updated = [];

        // Compare and update only if value changed
        foreach ($data as $key => $value) {
            $counterId = str_replace('counter-', '', $key);
            $newAmount = (int) $value;
            $oldAmount = isset($existingMap[$counterId]) ? (int) $existingMap[$counterId] : null;

            if ($oldAmount !== $newAmount) {
                $this->counters
                    ->set('amount', $newAmount)
                    ->where('counter_id', $counterId)
                    ->update();

                $updated[$counterId] = [
                    'old' => $oldAmount,
                    'new' => $newAmount,
                ];
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'updated' => $updated,
        ]);
    }public function updateSaldo()
    {
        $counterId = $this->request->getPost('counter_id');
        $action = $this->request->getPost('action');
        $inputAmount = (int) $this->request->getPost('amount'); 
        
        // Otomatis kalikan 1000 sesuai permintaan (nambah 3 angka nol)
        $realAmount = $inputAmount * 1000;

        $counter = $this->counters->find($counterId);
        if (!$counter) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $oldAmount = (int) $counter['amount'];
        $newAmount = $oldAmount;
        $operator = '';

        // Tentukan operasi tambah atau kurang
        if ($action === 'plus') {
            $newAmount += $realAmount;
            $operator = '+';
        } elseif ($action === 'minus') {
            $newAmount -= $realAmount;
            $operator = '-';
        }

        // Format angka ke ribuan (contoh: 435.000)
        $oldFormat = number_format($oldAmount, 0, ',', '.');
        $realFormat = number_format($realAmount, 0, ',', '.');
        $newFormat = number_format($newAmount, 0, ',', '.');

        // Format hasil persis seperti yang direquest
        $lastCalculation = "Sisa\n{$oldFormat} {$operator} {$realFormat}\n= {$newFormat}";

        // Update database
        $this->counters->update($counterId, [
            'amount' => $newAmount,
            'last_calculation' => $lastCalculation
        ]);

        return $this->response->setJSON([
            'success' => true,
            'new_amount_format' => number_format($newAmount, 0, ',', '.'),
            'last_calculation' => $lastCalculation
        ]);
    }
}
