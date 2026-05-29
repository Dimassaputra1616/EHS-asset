<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type',
        'asset_id',
        'consumable_id',
        'qty',
        'purpose',
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

    // Status badges helper
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 fw-semibold border border-warning border-opacity-10 rounded-pill"><i class="bi bi-clock me-1"></i> Pending</span>';
            case 'approved':
                return '<span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 fw-semibold border border-success border-opacity-10 rounded-pill"><i class="bi bi-check-circle me-1"></i> Disetujui</span>';
            case 'rejected':
                return '<span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 fw-semibold border border-danger border-opacity-10 rounded-pill"><i class="bi bi-x-circle me-1"></i> Ditolak</span>';
            case 'returned':
                return '<span class="badge bg-info bg-opacity-10 text-info px-2.5 py-1.5 fw-semibold border border-info border-opacity-10 rounded-pill"><i class="bi bi-arrow-return-left me-1"></i> Dikembalikan</span>';
            default:
                return '<span class="badge bg-secondary text-white px-2 py-1 rounded-pill">' . $this->status . '</span>';
        }
    }
}
