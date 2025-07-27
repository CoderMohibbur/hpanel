<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HostingLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hosting_id',
        'action',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'response' => 'array', // So you get decoded JSON automatically
    ];

    /**
     * Get the related hosting.
     */
    public function hosting()
    {
        return $this->belongsTo(Hosting::class);
    }

    /**
     * Scope: Filter logs by action name
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Accessor: Short response preview
     */
    public function getResponsePreviewAttribute(): string
    {
        return substr(strip_tags(json_encode($this->response)), 0, 100) . '...';
    }
}