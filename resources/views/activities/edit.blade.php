@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Edit Activity</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('activities.index') }}">Activities</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Activity</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                 <a href="{{ route('activities.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i> Back to List</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('activities.update', $activity->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="customer_id" name="customer_id" required>
                                        <option value="{{ $activity->customer_id }}" selected>{{ $activity->customer->name ?? 'Unknown Customer' }}</option>
                                    </select>
                                    @error('customer_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Activity Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="type" required>
                                        <option value="Call" {{ $activity->type == 'Call' ? 'selected' : '' }}>Call</option>
                                        <option value="Email" {{ $activity->type == 'Email' ? 'selected' : '' }}>Email</option>
                                        <option value="Meeting" {{ $activity->type == 'Meeting' ? 'selected' : '' }}>Meeting</option>
                                        <option value="Task" {{ $activity->type == 'Task' ? 'selected' : '' }}>Task</option>
                                        <option value="WhatsApp" {{ $activity->type == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
                                        <option value="Pending" {{ $activity->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" {{ $activity->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ $activity->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Follow-up Date</label>
                                    <input type="date" class="form-control" name="follow_up_date" value="{{ $activity->follow_up_date ? $activity->follow_up_date->format('Y-m-d') : '' }}">
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-bold">Notes</label>
                                    <textarea class="form-control" name="notes" rows="4" placeholder="Enter session notes, outcomes, or next steps...">{{ $activity->notes }}</textarea>
                                </div>

                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="ti ti-device-floppy me-1"></i> Update Activity
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @component('components.footer')
    @endcomponent
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#customer_id').select2({
            placeholder: 'Type customer name...',
            ajax: {
                url: '{{ route("activities.customers") }}', 
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endpush
