@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Reminders</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reminders</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Inactive Customers -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Inactive Customers (No purchase in 3 months)</h5>
                        <p class="card-text text-muted small">Customers who have not entered an order within the last 3 months.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Commodity</th>
                                        <th>Phone</th>
                                        <th>Last Order Date</th>
                                        <th>Total Orders</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inactiveCustomers as $customer)
                                    <tr>
                                        <td>
                                            <h6 class="fs-14 fw-medium">{{ $customer->name }}</h6>
                                            <span class="text-muted fs-12">{{ $customer->email }}</span>
                                        </td>
                                        <td>{{ $customer->commodity->name ?? '-' }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>
                                            @if($customer->orders->isNotEmpty())
                                                {{ $customer->orders->first()->invoice_date->format('d M Y') }}
                                            @else
                                                <span class="badge bg-warning">Never</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->orders->count() }}</td>
                                        <td>
                                            <a href="javascript:void(0);" 
                                               class="btn btn-sm btn-light" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#lastOrderModal" 
                                               data-id="{{ $customer->id }}" 
                                               data-name="{{ $customer->name }}">
                                               <i class="ti ti-eye"></i> View Last Order
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No inactive customers found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Birthday Customers -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Birthday Customers (This Month: {{ now()->format('F') }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Date of Birth</th>
                                        <th>Age</th>
                                        <th>Phone</th>
                                        <th>Commodity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($birthdayCustomers as $customer)
                                    <tr>
                                        <td>
                                            <h6 class="fs-14 fw-medium">{{ $customer->name }}</h6>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($customer->date_of_birth)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($customer->date_of_birth)->age }} Years</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->commodity->name ?? '-' }}</td>
                                        <td>
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customer->phone)) }}?text=Happy%20Birthday%20{{ urlencode($customer->name) }}!%20Hope%20you%20have%20a%20great%20day!" 
                                               target="_blank" 
                                               class="btn btn-sm btn-success">
                                               <i class="ti ti-brand-whatsapp"></i> Send WA
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No customers with birthday this month.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Last Order Modal -->
    <div class="modal fade" id="lastOrderModal" tabindex="-1" aria-labelledby="lastOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lastOrderModalLabel">Last Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loadingUtils" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="orderDetails" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Invoice No:</strong> <span id="modalInvoiceNo"></span><br>
                                <strong>Date:</strong> <span id="modalDate"></span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <strong>Total Amount:</strong> <span id="modalTotal"></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="modalItemsBody">
                                    <!-- Items will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="errorMsg" class="alert alert-danger" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @component('components.footer')
    @endcomponent

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lastOrderModal = document.getElementById('lastOrderModal');
            lastOrderModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var customerId = button.getAttribute('data-id');
                var customerName = button.getAttribute('data-name');
                
                var modalTitle = lastOrderModal.querySelector('.modal-title');
                modalTitle.textContent = 'Last Order Details - ' + customerName;

                var loading = document.getElementById('loadingUtils');
                var details = document.getElementById('orderDetails');
                var errorDiv = document.getElementById('errorMsg');
                var tbody = document.getElementById('modalItemsBody');

                loading.style.display = 'block';
                details.style.display = 'none';
                errorDiv.style.display = 'none';
                tbody.innerHTML = '';

                fetch('{{ route("reminders.last-order", ":id") }}'.replace(':id', customerId))
                    .then(response => response.json())
                    .then(data => {
                        loading.style.display = 'none';
                        if(data.status === 'success') {
                            details.style.display = 'block';
                            var order = data.data;
                            
                            document.getElementById('modalInvoiceNo').textContent = order.invoice_no;
                            
                            // Format Date
                            var date = new Date(order.invoice_date);
                            document.getElementById('modalDate').textContent = date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                            
                            // Format Currency (Simple)
                            document.getElementById('modalTotal').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(order.total_amount);

                            // Items
                            if(order.items && order.items.length > 0) {
                                order.items.forEach(item => {
                                    var productName = item.product ? item.product.name : 'Unknown Product';
                                    var price = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.price);
                                    var subtotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.subtotal);
                                    
                                    var row = `<tr>
                                        <td>${productName}</td>
                                        <td class="text-center">${item.qty}</td>
                                        <td class="text-end">${price}</td>
                                        <td class="text-end">${subtotal}</td>
                                    </tr>`;
                                    tbody.innerHTML += row;
                                });
                            } else {
                                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No items found.</td></tr>';
                            }

                        } else {
                            errorDiv.textContent = data.message;
                            errorDiv.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        loading.style.display = 'none';
                        errorDiv.textContent = 'Error loading data: ' + error;
                        errorDiv.style.display = 'block';
                    });
            });
        });
    </script>
    @endpush
</div>
@endsection
