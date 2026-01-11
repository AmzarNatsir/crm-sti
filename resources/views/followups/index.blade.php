@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Follow-up Dashboard</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Follow-ups</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                 <a href="{{ route('activities.index') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Log Activity</a>
            </div>
        </div>

        <div class="row">
            <!-- Scheduled Follow-ups -->
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card w-100 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-white"><i class="ti ti-calendar me-2"></i>Scheduled</h5>
                        <span class="badge bg-white text-primary">{{ $scheduledFollowups->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($scheduledFollowups as $followup)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $followup->customer->name ?? 'Unknown Customer' }}</h6>
                                        <small class="{{ $followup->follow_up_date < now() ? 'text-danger fw-bold' : 'text-muted' }}">
                                            {{ $followup->follow_up_date->format('d M') }}
                                        </small>
                                    </div>
                                    <p class="mb-1 text-truncate" style="max-width: 250px;">{{ $followup->notes ?? 'No notes' }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted"><i class="ti ti-user"></i> {{ $followup->user->name ?? 'Unassigned' }}</small>
                                        <a href="{{ route('customers.edit', $followup->customer_id ?? 0) }}" class="btn btn-xs btn-outline-primary">Open</a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    <i class="ti ti-calendar-off fs-1 mb-2"></i>
                                    <p>No scheduled follow-ups.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prospects in Follow-up -->
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card w-100 border-0 shadow-sm">
                    <div class="card-header bg-warning text-white d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-white"><i class="ti ti-clock-exclamation me-2"></i>Prospects in Follow-up</h5>
                        <span class="badge bg-white text-warning">{{ $followupProspects->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                         <div class="list-group list-group-flush">
                            @forelse($followupProspects as $prospect)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $prospect->name }}</h6>
                                        <small class="text-muted">Since {{ $prospect->updated_at->format('d M') }}</small>
                                    </div>
                                    <p class="mb-1">Commodity: {{ $prospect->commodity->name ?? '-' }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted"><i class="ti ti-user"></i> {{ $prospect->creator->name ?? 'Unknown' }}</small>
                                        <button type="button" 
                                            class="btn btn-xs btn-outline-warning btn-update-status" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal"
                                            data-id="{{ $prospect->id }}"
                                            data-name="{{ $prospect->name }}">
                                            Update Status
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    <i class="ti ti-check fs-1 mb-2"></i>
                                    <p>No prospects in follow-up.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Status Modal -->
            <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="updateStatusForm" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Update Follow-up Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Updating status for: <strong id="modalProspectName"></strong></p>
                                <div class="mb-3">
                                    <label class="form-label">New Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="customer">Promote to Customer</option>
                                        <option value="lost">Lost</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="Enter details about this update..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var updateStatusModal = document.getElementById('updateStatusModal');
                    updateStatusModal.addEventListener('show.bs.modal', function (event) {
                        var button = event.relatedTarget;
                        var id = button.getAttribute('data-id');
                        var name = button.getAttribute('data-name');
                        
                        var modalTitle = updateStatusModal.querySelector('#modalProspectName');
                        var form = updateStatusModal.querySelector('#updateStatusForm');
                        
                        modalTitle.textContent = name;
                        form.action = '/followups/' + id + '/update-status';
                    });
                });
            </script>

            <!-- Post-Purchase Follow-up -->
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card w-100 border-0 shadow-sm">
                    <div class="card-header bg-success text-white d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-white"><i class="ti ti-shopping-cart-heart me-2"></i>Post-Purchase</h5>
                        <span class="badge bg-white text-success">{{ $postPurchaseCustomers->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                         <div class="list-group list-group-flush">
                            @forelse($postPurchaseCustomers as $customer)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $customer->name }}</h6>
                                        <small class="text-success">Bought {{ $customer->orders->first()->invoice_date->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Last Order: {{ number_format($customer->orders->first()->total_amount ?? 0) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted"><i class="ti ti-box"></i> {{ $customer->orders->first()->items->count() ?? 0 }} Items</small>
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customer->phone)) }}?text=Hi%20{{ urlencode($customer->name) }},%20how%20is%20your%20recent%20purchase?%20We%20hope%20everything%20is%20growing%20well!" target="_blank" class="btn btn-xs btn-outline-success"><i class="ti ti-brand-whatsapp"></i> Check In</a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    <i class="ti ti-thumb-up fs-1 mb-2"></i>
                                    <p>No recent purchase follow-ups.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @component('components.footer')
    @endcomponent
</div>
@endsection
