<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Plan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'plans';

    protected $fillable = [
        'user_id',
        'nama_rencana',
        'kategori',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',

        'keterlambatan_menit',
        'overtime_menit',
    ];
}