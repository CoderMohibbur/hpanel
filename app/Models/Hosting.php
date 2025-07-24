<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Hosting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'domain',
        'package',
        'plan',
        'cyberpanel_status',
        'cyberpanel_message',
        'ssl',
        'expiry_date',
    ];

    protected $casts = [
        'ssl' => 'boolean',
        'expiry_date' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Is hosting expired?
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date ? $this->expiry_date->isPast() : false;
    }

    /**
     * Accessor: Formatted expiry date
     */
    public function getExpiryFormattedAttribute(): ?string
    {
        return $this->expiry_date ? $this->expiry_date->format('d M Y') : null;
    }

    /**
     * Scope: Filter active hostings
     */
    public function scopeActive($query)
    {
        return $query->where('cyberpanel_status', 'success')
                     ->where(function ($q) {
                         $q->whereNull('expiry_date')
                           ->orWhere('expiry_date', '>', Carbon::now());
                     });
    }

    /**
     * Scope: Filter by plan
     */
    public function scopeByPlan($query, string $plan)
    {
        return $query->where('plan', $plan);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('cyberpanel_status', $status);
    }
}
