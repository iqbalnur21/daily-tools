<?php

namespace App\Models;

use CodeIgniter\Model;

class SeriesEpisodes extends Model
{
    protected $table      = 'series_episodes';
    protected $primaryKey = 'id';
    protected $useTimestamps  = true;
    protected $allowedFields  = ['season_id', 'ep_num', 'status', 'watched_at'];
}
