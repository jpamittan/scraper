<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleMapData extends Model
{
    use HasFactory;

    protected $table = "google_map_data";
    protected $guarded = [];
}
