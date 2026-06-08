<?php

namespace App\Models;


// Gunakan class Model dari package MongoDB:
use MongoDB\Laravel\Eloquent\Model;

class Activity extends Model
{
    // Tentukan nama collection di MongoDB
    protected $collection = 'activities';
    
protected $fillable = [
    'plan_id',
    'nama_aktivitas',
    'kategori',
    'tanggal',
    'jam_mulai',
    'jam_selesai',
    'durasi',
    'status',
    'deskripsi',
    'user_id',
];
}