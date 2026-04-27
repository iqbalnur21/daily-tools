<?php

namespace App\Models;

use CodeIgniter\Model;

class SeriesSeasons extends Model
{
    protected $table      = 'series_seasons';
    protected $primaryKey = 'id';
    protected $useTimestamps  = true;
    protected $allowedFields  = ['series_id', 'season_num', 'total_eps'];
}
