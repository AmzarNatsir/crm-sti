<?php
namespace App\Listeners;

use App\Events\LeadStatusChanged;
use App\Models\Activity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAutoFollowUp implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LeadStatusChanged $event): void
    {
        match ($event->newStatus) {
            'new' => $this->create($event->lead, 1),
            'contacted' => $this->create($event->lead, 3),
            'proposal' => $this->create($event->lead, 7),
            default => null,
        };
    }

    private function create($lead, int $days): void
    {
        Activity::create([
            'customer_id' => $lead->customer_id,
            'lead_id' => $lead->id,
            'user_id' => $lead->assigned_to,
            'type' => 'call',
            'follow_up_date' => now()->addDays($days),
            'notes' => 'Auto follow-up by system',
        ]);
    }
}
