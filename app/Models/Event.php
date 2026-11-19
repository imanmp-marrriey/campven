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
        'harga',
        'skpi_poin',
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

    public function activeParticipants()
    {
        return $this->hasMany(Participant::class)->whereIn('status', ['registered', 'confirmed', 'attended']);
    }

    public function participantCount()
    {
        return $this->activeParticipants()->count();
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

    public function formattedHarga()
    {
        if (empty($this->harga) || $this->harga == 0) {
            return 'GRATIS';
        }
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function persentaseTerisi()
    {
        if (!$this->kuota || $this->kuota == 0) {
            return 0;
        }
        return min(100, round(($this->participantCount() / $this->kuota) * 100));
    }
}
