<?php

use App\Models\Lead;

class LeadWorkflowService
{
    public function move(Lead $lead, string $toStatus)
    {
        $from = $lead->status;

        if (! $this->allowed($lead, $from, $toStatus)) {
            throw new \Exception("Invalid lead transition: $from → $toStatus");
        }

        $lead->update(['status' => $toStatus]);

        $this->afterTransition($lead, $toStatus);
    }

    protected function allowed(Lead $lead, $from, $to)
    {
        return match ([$from, $to]) {
            ['new', 'contacted'] =>
                $lead->activities()->exists(),

            ['contacted', 'qualified'] =>
                $lead->score >= 50,

            ['qualified', 'proposal'] =>
                $lead->customer->quotations()->exists(),

            ['proposal', 'won'] =>
                $lead->customer->orders()->exists(),

            ['proposal', 'lost'] =>
                !empty($lead->lost_reason),

            default => false,
        };
    }

    protected function afterTransition(Lead $lead, string $status)
    {
        if ($status === 'won') {
            $lead->customer->update(['type' => 'customer']);
        }
    }
}
