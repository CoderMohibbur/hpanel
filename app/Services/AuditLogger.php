<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLogger
{
    /**
     * Create an audit log record.
     *
     * @param  int|null  $actorId
     * @param  string    $action
     * @param  string|null $targetType
     * @param  int|null  $targetId
     * @param  array<string,mixed>|null $oldValues
     * @param  array<string,mixed>|null $newValues
     */
    public function log(
        ?int $actorId,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): void {
        $ip = $request?->ip();
        $ua = $request?->userAgent();

        AuditLog::create([
            'actor_id' => $actorId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip' => $ip ? Str::limit($ip, 45, '') : null,
            'user_agent' => $ua ? Str::limit($ua, 2000) : null,
        ]);
    }
}
