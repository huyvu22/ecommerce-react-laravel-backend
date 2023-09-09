<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteExpiredTokens extends Command
{

    protected $signature = 'tokens:delete-expired';
    protected $description = 'Delete records with expired remember_token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculate the date 30 days ago
        $cutoffDate = now()->subDays(2);

        // Delete records where remember_token is not null and created_at is older than $cutoffDate
        User::whereNotNull('remember_token')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info('Expired tokens have been deleted.');
    }
}
