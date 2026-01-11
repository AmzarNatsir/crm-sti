<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Basic info
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,

            // CRM status
            'status' => $this->status,
            'source' => $this->source,

            // Assigned sales
            'assigned_sales' => $this->whenLoaded('assignedSales', function () {
                return [
                    'id' => $this->assignedSales->id,
                    'name' => $this->assignedSales->name,
                    'email' => $this->assignedSales->email,
                ];
            }),

            // // Lead summary
            // 'leads' => LeadResource::collection(
            //     $this->whenLoaded('leads')
            // ),

            // // Activity summary (last 5)
            // 'activities' => ActivityResource::collection(
            //     $this->whenLoaded('activities')
            // ),

            // Computed fields
            'last_contacted_at' => $this->activities
                ? optional($this->activities->sortByDesc('created_at')->first())->created_at
                : null,

            'total_leads' => $this->whenCounted('leads'),

            // Meta
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
