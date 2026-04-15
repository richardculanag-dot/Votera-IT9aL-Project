<?php
// FILE: app/Models/AuditLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model', 'model_id', 'description', 'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Static helper — call this anywhere to log ──────────
    public static function record(string $action, string $description, string $model = null, int $modelId = null): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}