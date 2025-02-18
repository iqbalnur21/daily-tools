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
        $data['counters'] = $this->counters->findAll();
        return view('home', $data);
    }
    public function update()
    {
        parse_str($this->request->getBody(), $data);
        foreach ($data as $key => $value) {
            $counterId = str_replace('counter-', '', $key);
            $this->counters->set('amount', $value)->where('counter_id', $counterId)->update();
        }
        return $this->response->setJSON(['success' => true, 'message' => $data]);
    }
}
