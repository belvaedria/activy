<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Plan extends Model
{
    protected $collection = 'plans';

protected $fillable = [
    'nama_rencana',
    'kategori',
    'tanggal',
    'jam_mulai',
    'jam_selesai',
    'status',
    'user_id',
];
}