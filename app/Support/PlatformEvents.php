<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\InAppNotification;
use Illuminate\Support\Facades\Schema;

class PlatformEvents
{
    public static function log(?int $userId, string $action, ?string $entityType = null, $entityId = null, array $meta = []): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'meta' => $meta,
        ]);
    }

    public static function notify(int $userId, string $title, string $message): void
    {
        if (! Schema::hasTable('in_app_notifications')) {
            return;
        }

        InAppNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}
