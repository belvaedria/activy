<?php

namespace App\Models;

// Hapus atau comment baris ini:
// use Illuminate\Database\Eloquent\Model;

// Gunakan class Model dari package MongoDB:
use MongoDB\Laravel\Eloquent\Model;

class Activity extends Model
{
    // Tentukan nama collection di MongoDB
    protected $collection = 'activities';

    // Tentukan field apa saja yang boleh diisi (berdasarkan UI kamu)
    protected $fillable = [
        'nama_aktivitas',
        'kategori',
        'tanggal',
        'durasi',
        'deskripsi'
    ];
}