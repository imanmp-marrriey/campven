<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_event',
        'kategori',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'deskripsi',
        'poster',
        'created_by',
        'kuota',
    ];
    protected $casts = [
        'tanggal'       => 'date:Y-m-d',
        'waktu_mulai'   => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function participantCount()
    {
        return $this->participants()->count();
    }

    public function isFull()
    {
        if (!$this->kuota) {
            return false;
        }
        return $this->participantCount() >= $this->kuota;
    }

    public function sisaKuota()
    {
        if (!$this->kuota) {
            return null;
        }
        return max(0, $this->kuota - $this->participantCount());
    }
}
