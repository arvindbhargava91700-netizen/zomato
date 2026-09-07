<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class AutoCloseTickets extends Command
{
    protected $signature = 'tickets:autoclose {--days=3 : Days after which resolved tickets are auto-closed}';
    protected $description = 'Auto-close tickets that have been resolved for more than the given number of days without a user response';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $tickets = Ticket::where('status', 'resolved')
            ->whereNotNull('resolved_at')
            ->where('resolved_at', '<=', now()->subDays($days))
            ->get();

        $count = 0;
        foreach ($tickets as $ticket) {
            // Only auto-close if the last message is from admin (user has not replied)
            $last = $ticket->messages()->latest('id')->first();
            if ($last && $last->sender_type === 'admin') {
                $ticket->markStatus('closed', $ticket->resolution_note);
                $count++;
            }
        }

        $this->info("Auto-closed {$count} ticket(s).");
        return self::SUCCESS;
    }
}
