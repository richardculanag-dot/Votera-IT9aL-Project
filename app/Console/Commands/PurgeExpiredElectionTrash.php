<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Election;
use Illuminate\Console\Command;

class PurgeExpiredElectionTrash extends Command
{
    protected $signature = 'elections:purge-expired-trash';

    protected $description = 'Permanently delete elections that have been in the trash longer than the retention period.';

    public function handle(): int
    {
        $cutoff = now()->subDays(Election::TRASH_RETENTION_DAYS);

        $expired = Election::onlyTrashed()
            ->where('deleted_at', '<', $cutoff)
            ->get();

        if ($expired->isEmpty()) {
            $this->info('No expired trashed elections to purge.');

            return self::SUCCESS;
        }

        $count = $expired->count();

        foreach ($expired as $election) {
            $election->forceDelete();
        }

        AuditLog::create([
            'user_id' => null,
            'action' => 'election_trash_auto_purged',
            'model' => 'Election',
            'model_id' => null,
            'description' => "Scheduled purge removed {$count} election(s) from trash after ".Election::TRASH_RETENTION_DAYS.' days in trash.',
            'ip_address' => null,
        ]);

        $this->info("Purged {$count} election(s) from trash.");

        return self::SUCCESS;
    }
}
