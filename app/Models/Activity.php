<?php

namespace App\Models;


// Gunakan class Model dari package MongoDB:
use MongoDB\Laravel\Eloquent\Model;

class Activity extends Model
{
    // Tentukan nama collection di MongoDB
    protected $collection = 'activities';

    // Tentukan field apa saja yang boleh diisi (berdasarkan UI kamu)
    protected $fillable = [
        'user_id',
        'plan_id',
        'nama_aktivitas',
        'kategori',
        'tanggal',
        'durasi',
        'deskripsi'
    ];
}