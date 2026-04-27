<?php

namespace App\Models;

use CodeIgniter\Model;

class SeriesTracker extends Model
{
    protected $table      = 'series_tracker';
    protected $primaryKey = 'id';
    protected $useTimestamps  = true;
    protected $allowedFields  = ['title', 'type', 'notes', 'rating', 'disabled'];
}
