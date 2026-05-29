<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
        'consumable_id',
        'item_name',
        'description',
        'photo',
        'urgency',
        'status',
        'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    // Urgency badges helper
    public function getUrgencyBadgeAttribute()
    {
        switch ($this->urgency) {
            case 'low':
                return '<span class="badge bg-info bg-opacity-10 text-info px-2.5 py-1.5 fw-semibold border border-info border-opacity-10 rounded-pill"><i class="bi bi-info-circle me-1"></i> Rendah</span>';
            case 'medium':
                return '<span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 fw-semibold border border-warning border-opacity-10 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i> Sedang</span>';
            case 'high':
                return '<span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 fw-semibold border border-danger border-opacity-10 rounded-pill"><i class="bi bi-shield-fill-exclamation me-1"></i> Tinggi (Urgen)</span>';
            default:
                return '<span class="badge bg-secondary text-white px-2 py-1 rounded-pill">' . $this->urgency . '</span>';
        }
    }

    // Status badges helper
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 fw-semibold border border-warning border-opacity-10 rounded-pill"><i class="bi bi-clock me-1"></i> Menunggu</span>';
            case 'investigating':
                return '<span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1.5 fw-semibold border border-primary border-opacity-10 rounded-pill"><i class="bi bi-search me-1"></i> Diperiksa</span>';
            case 'resolved':
                return '<span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 fw-semibold border border-success border-opacity-10 rounded-pill"><i class="bi bi-check-all me-1"></i> Selesai (Diperbaiki)</span>';
            case 'closed':
                return '<span class="badge bg-secondary bg-opacity-10 text-secondary px-2.5 py-1.5 fw-semibold border border-secondary border-opacity-10 rounded-pill"><i class="bi bi-archive me-1"></i> Ditutup</span>';
            default:
                return '<span class="badge bg-secondary text-white px-2 py-1 rounded-pill">' . $this->status . '</span>';
        }
    }
}
