@extends('layout.mainlayout')
@section('content')

<style>
    .fc-event-title {
        white-space: pre-wrap !important;
        overflow: visible !important;
        word-wrap: break-word;
    }
    .fc-daygrid-event {
        white-space: pre-wrap !important;
        height: auto !important;
        min-height: 20px;
        padding: 2px 0;
    }
    .fc-event-main {
        white-space: pre-wrap !important;
    }
    /* Style for read-only fields */
    .form-control-plaintext {
        padding-left: 0;
        font-weight: bold;
    }
</style>

<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Delivery Schedule</h4>
            </div>
            <div class="flex-shrink-0">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                    <i class="ti ti-plus me-1"></i>Add Schedule
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Delivery Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addScheduleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Filter by Invoice Date</label>
                        <input type="date" class="form-control" id="invoiceDateFilter">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Invoice <span class="text-danger">*</span></label>
                        <select class="form-select select2-modal" name="order_id" required id="invoiceSelect">
                            <option value="">Select Invoice</option>
                        </select>
                    </div>
                    <div id="invoiceInfo" class="mb-3 p-2 bg-light border d-none">
                        <small class="d-block text-muted">Customer: <span id="infoCustomer" class="text-dark fw-bold"></span></small>
                        <small class="d-block text-muted">Total Invoice: <span id="infoTotal" class="text-dark fw-bold"></span></small>
                        <small class="d-block text-muted">Total Items: <span id="infoItems" class="text-dark fw-bold"></span></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="delivery_date" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Personnel <span class="text-danger">*</span></label>
                        <select class="form-select select2-modal" name="employee_id[]" multiple="multiple" required>
                            @foreach($employees as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Delivery Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editScheduleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="schedule_id" id="editScheduleId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label d-block text-muted">Invoice</label>
                        <p id="editInvoiceNo" class="fw-bold mb-1"></p>
                    </div>
                    <div class="mb-3 p-2 bg-light border">
                        <small class="d-block text-muted">Customer: <span id="editCustomer" class="text-dark fw-bold"></span></small>
                        <small class="d-block text-muted">Total Invoice: <span id="editTotal" class="text-dark fw-bold"></span></small>
                    </div>
                    
                    <!-- Editable Fields -->
                    <div class="mb-3">
                        <label class="form-label">Delivery Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="delivery_date" id="editDeliveryDateInput" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Personnel <span class="text-danger">*</span></label>
                        <select class="form-select select2-modal-edit" name="employee_id[]" multiple="multiple" id="editPersonnelSelect" required>
                            @foreach($employees as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="arrivalDateGroup">
                        <label class="form-label">Arrival Date (To Complete)</label>
                        <input type="date" class="form-control" name="arrival_date" id="arrivalDateInput">
                        <small class="text-muted">Fill this to mark as completed</small>
                    </div>
                    
                    <div id="completionNotice" class="alert alert-success d-none">
                        Delivery completed on <span id="completedOnDate"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="deleteScheduleBtn">Delete</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- FullCalendar CSS and JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');
        let events = @json($events);

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: events,
            editable: true, // Enable dragging
            eventDrop: function(info) {
                // Handle Drag & Drop
                let id = info.event.id;
                let newDate = info.event.startStr; // YYYY-MM-DD
                let invoiceDate = info.event.extendedProps.invoice_date;

                if (invoiceDate && newDate < invoiceDate) {
                    Swal.fire('Error', 'Delivery date cannot be before invoice date (' + invoiceDate + ')', 'error');
                    info.revert();
                    return;
                }
                
                $.ajax({
                    url: `/delivery-schedule/${id}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        delivery_date: newDate
                    },
                    success: function(res) {
                        if (res.success) {
                            // Update extendedProps to match new state
                            info.event.setExtendedProp('delivery_date', newDate);
                            
                            // Optional: Show toast
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Schedule updated',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(err) {
                        info.revert(); // Revert on failure
                        Swal.fire('Error', err.responseJSON.message || 'Update failed', 'error');
                    }
                });
            },
            eventClick: function(info) {
                let status = info.event.extendedProps.status;
                let props = info.event.extendedProps;

                $('#editScheduleId').val(info.event.id);
                $('#editInvoiceNo').text(props.invoice_no);
                $('#editCustomer').text(props.customer);
                $('#editTotal').text(props.total_amount);
                
                // Set Editable Fields - Use startStr for current visual date
                // Fallback to props.delivery_date if startStr is somehow missing, but startStr is reliable for current position
                let currentDate = info.event.startStr;
                $('#editDeliveryDateInput').val(currentDate);
                
                // Set Personnel Select2
                let personnelIds = props.personnel_ids || [];
                $('#editPersonnelSelect').val(personnelIds).trigger('change');
                
                // Reset fields
                let invoiceDate = props.invoice_date || '';
                $('#editDeliveryDateInput').prop('disabled', false).prop('readonly', false).attr('min', invoiceDate);
                $('#editPersonnelSelect').prop('disabled', false);
                $('#arrivalDateGroup').addClass('d-none');
                $('#arrivalDateInput').val('').prop('required', false).attr('min', invoiceDate);
                $('#updateBtn').removeClass('d-none').text('Update Schedule');
                $('#deleteScheduleBtn').removeClass('d-none');
                $('#completionNotice').addClass('d-none');

                if (status === 'completed') {
                    // Lock fields for completed
                    $('#editDeliveryDateInput').prop('disabled', true);
                    $('#editPersonnelSelect').prop('disabled', true);
                    
                    $('#updateBtn').addClass('d-none');
                    $('#deleteScheduleBtn').addClass('d-none');
                    $('#completionNotice').removeClass('d-none');
                    $('#completedOnDate').text(props.display_delivery_date);
                } else if (status === 'approved') {
                    // Approved: Lock Details, Enable Arrival Date
                    $('#editDeliveryDateInput').prop('readonly', true); // different visual than disabled
                    $('#editPersonnelSelect').prop('disabled', true);
                    
                    $('#arrivalDateGroup').removeClass('d-none');
                    $('#arrivalDateInput').prop('required', true);
                    
                    $('#updateBtn').text('Complete Delivery');
                    // Hide delete button or keep it? Requirements say "cannot be changed", probably implies cannot be deleted either.
                    $('#deleteScheduleBtn').addClass('d-none');
                } else {
                    // Submitted/Open: All Editable
                    // Everything is already reset to editable above
                }

                $('#editScheduleModal').modal('show');
            }
        });
        calendar.render();

        // Initialize Select2 in modals
        $('.select2-modal').select2({
            dropdownParent: $('#addScheduleModal'),
            width: '100%',
            placeholder: "Select Personnel"
        });
        
        $('.select2-modal-edit').select2({
            dropdownParent: $('#editScheduleModal'),
            width: '100%',
            placeholder: "Select Personnel"
        });

        // Load Invoices
        function loadInvoices(date = '') {
            let select = $('#invoiceSelect');
            if (!date) {
                select.empty().append('<option value="">Select Invoice</option>');
                $('#invoiceInfo').addClass('d-none');
                return;
            }
            $.get('{{ route("delivery-schedule.invoices") }}', { invoice_date: date }, function(data) {
                select.empty().append('<option value="">Select Invoice</option>');
                window._openInvoices = data;
                data.forEach(function(invoice) {
                    select.append(`<option value="${invoice.id}">${invoice.invoice_no} - ${invoice.customer.name}</option>`);
                });
            });
        }

        $('#invoiceDateFilter').on('change', function() {
            loadInvoices($(this).val());
        });

        $('#addScheduleModal').on('show.bs.modal', function() {
            $('#invoiceDateFilter').val('');
            $('#invoiceSelect').empty().append('<option value="">Select Invoice</option>');
            $('#invoiceInfo').addClass('d-none');
            // Reset personnel
            $('select[name="employee_id[]"]').val(null).trigger('change');
        });

        $('#invoiceSelect').on('change', function() {
            let id = $(this).val();
            if (id) {
                let invoice = window._openInvoices.find(i => i.id == id);
                if (invoice) {
                    $('#infoCustomer').text(invoice.customer.name);
                    $('#infoTotal').text(parseFloat(invoice.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2}));
                    $('#infoItems').text(invoice.total_items || 0);
                    $('#invoiceInfo').removeClass('d-none');
                    
                    // Set min date for delivery date input
                    if (invoice.invoice_date) {
                        let invoiceDate = invoice.invoice_date.split(' ')[0]; // Handle potential time part
                        $('input[name="delivery_date"]').attr('min', invoiceDate);
                    }
                }
            } else {
                $('#invoiceInfo').addClass('d-none');
                $('input[name="delivery_date"]').removeAttr('min');
            }
        });

        // Handle Add Form
        $('#addScheduleForm').on('submit', function(e) {
            e.preventDefault();
            // Client-side validation just in case
            let invDate = $('input[name="delivery_date"]').attr('min');
            let delDate = $('input[name="delivery_date"]').val();
            if (invDate && delDate && delDate < invDate) {
                Swal.fire('Error', 'Delivery date cannot be before invoice date', 'error');
                return;
            }
            $.ajax({
                url: '{{ route("delivery-schedule.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#addScheduleModal').modal('hide');
                        location.reload();
                    }
                },
                error: function(err) {
                    Swal.fire('Error', err.responseJSON.message || 'Something went wrong', 'error');
                }
            });
        });

        // Handle Update Form
        $('#editScheduleForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#editScheduleId').val();
            $.ajax({
                url: `/delivery-schedule/${id}`,
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#editScheduleModal').modal('hide');
                        location.reload();
                    }
                },
                error: function(err) {
                    Swal.fire('Error', err.responseJSON.message || 'Something went wrong', 'error');
                }
            });
        });

        // Handle Delete
        $('#deleteScheduleBtn').on('click', function() {
            let id = $('#editScheduleId').val();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/delivery-schedule/${id}`,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            if (res.success) {
                                $('#editScheduleModal').modal('hide');
                                location.reload();
                            }
                        },
                        error: function(err) {
                            Swal.fire('Error', err.responseJSON.message || 'Something went wrong', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
