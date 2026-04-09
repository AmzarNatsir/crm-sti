@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0 d-flex align-items-center"><a href="{{route('sales.index')}}" class=""><i class="ti ti-chevron-left me-1 fs-14"></i>Sales</a></h6>
                </div>
            </div>

            <div class="card rounded-0 mb-0">
                <div class="card-header">
                    <h6 class="fw-bold m-0"> Edit Sale {{$order->invoice_no}}</h6>
                </div>

                <form action="{{route('sales.update', $order->id)}}" method="POST" id="sales-form">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{$order->customer_id == $customer->id ? 'selected' : ''}}>{{$customer->name}} ({{$customer->type}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Invoice No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="invoice_no" value="{{$order->invoice_no}}" required>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                    <div class="input-group w-auto input-group-flat">
                                        <input type="text" class="form-control" 
                                        name="invoice_date" 
                                        value="{{$order->invoice_date->format('Y-m-d')}}" 
                                        placeholder="dd/mm/yyyy" 
                                        required
                                        data-provider="flatpickr" 
                                        data-date-format="Y-m-d" 
                                        data-alt-input="true" 
                                        data-alt-format="d M, Y">
                                        <span class="input-group-text">
                                            <i class="ti ti-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="payment_method_id" required>
                                        <option value="">Select Method</option>
                                        @foreach($payment_methods as $method)
                                            <option value="{{$method->id}}" {{$order->payment_method_id == $method->id ? 'selected' : ''}}>{{$method->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Campaign</label>
                                    <select class="form-select select2" name="compaign_id">
                                        <option value="">Select Campaign</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{{$campaign->id}}" {{$order->compaign_id == $campaign->id ? 'selected' : ''}}>{{$campaign->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Payment Status</label>
                                    <select class="form-select" name="payment_status">
                                        <option value="paid" {{$order->payment_status == 'paid' ? 'selected' : ''}}>Paid</option>
                                        <option value="unpaid" {{$order->payment_status == 'unpaid' ? 'selected' : ''}}>Unpaid</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="mb-3">
                                    <table class="table invoice-table border" id="items-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%;">Product</th>
                                                <th style="width: 15%;">Type</th>
                                                <th>Price</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="invoices-list">
                                            @foreach($order->items as $index => $item)
                                            <tr class="invoices-list-item">
                                                <td>
                                                    <select class="form-select product-select select2" name="items[{{$index}}][product_id]" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            @php
                                                                $prices = $product->prices->pluck('price', 'type')->toArray();
                                                            @endphp
                                                            <option value="{{$product->id}}" 
                                                                {{$item->product_id == $product->id ? 'selected' : ''}}
                                                                data-price-cs="{{ isset($prices['CS']) ? (int)$prices['CS'] : 0 }}"
                                                                data-price-r1="{{ isset($prices['R1']) ? (int)$prices['R1'] : 0 }}"
                                                                data-price-r2="{{ isset($prices['R2']) ? (int)$prices['R2'] : 0 }}" 
                                                                data-price-fg="{{ isset($prices['FG']) ? (int)$prices['FG'] : 0 }}">
                                                                {{$product->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select type-select" name="items[{{$index}}][price_type]" required>
                                                        <option value="CS" {{$item->price_type == 'CS' ? 'selected' : ''}}>CS</option>
                                                        <option value="R1" {{$item->price_type == 'R1' ? 'selected' : ''}}>R1</option>
                                                        <option value="R2" {{$item->price_type == 'R2' ? 'selected' : ''}}>R2</option>
                                                        <option value="FG" {{$item->price_type == 'FG' ? 'selected' : ''}}>FG</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control price-input" name="items[{{$index}}][price]" value="{{ (int)$item->price }}" required></td>
                                                <td><input type="number" class="form-control qty-input" name="items[{{$index}}][qty]" value="{{$item->qty}}" min="1" required></td>
                                                <td><input type="text" class="form-control subtotal-input" readonly value="{{ (int)$item->subtotal }}"></td>
                                                <td><button type="button" class="btn remove-item btn-sm text-danger"><i class="ti ti-trash"></i></button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" id="add-item"><i class="ti ti-plus me-1"></i>Add Item</button>
                                </div>
                            </div>

                            <div class="col-lg-8"></div>
                            <div class="col-lg-4">
                                <div class="border p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fs-14 fw-normal text-dark">Subtotal</h6>
                                        <h6 class="fs-14 fw-semibold text-dark" id="grand-subtotal">0</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fs-14 fw-normal text-dark">Discount</h6>
                                        <input type="text" class="form-control form-control-sm w-50" name="invoice_discount" id="invoice-discount" value="{{ (int)$order->invoice_discount }}">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fs-14 fw-normal text-dark">Shipping Cost (Rp)</h6>
                                        <input type="text" class="form-control form-control-sm w-50" name="shipping_cost" id="shipping-cost" value="{{ (int)$order->shipping_cost }}">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2 border-top pt-2">
                                        <h6 class="fs-18 fw-bold">Total</h6>
                                        <h6 class="fs-18 fw-bold" id="grand-total">0</h6>
                                        <input type="hidden" name="total_amount" id="total-amount-hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-light me-2" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Sale</button>
                    </div>
                </form>
            </div>
        </div>

        @component('components.footer')
        @endcomponent
    </div>

@endsection

@push('scripts')
<script src="{{URL::asset('build/plugins/inputmask/inputmask.min.js')}}"></script>
<script>
    function initMask() {
        if(typeof Inputmask !== "undefined"){
            Inputmask({
                alias: "numeric",
                groupSeparator: ".",
                radixPoint: ",",
                autoGroup: true,
                digits: 0,
                digitsOptional: false,
                prefix: "",
                placeholder: "0",
                removeMaskOnSubmit: false,
                rightAlign: false,
                allowMinus: false
            }).mask(document.querySelectorAll(".price-input, #invoice-discount, #shipping-cost"));
        }
    }
    $(document).ready(function() {
        initMask();
        let itemCount = {{ count($order->items) }};

        // Initialize Select2
        function initSelect2(element) {
            element.select2({
                width: '100%',
                placeholder: 'Select an option'
            });
        }

        initSelect2($('.select2'));

        // Calculate initial rows and grand total
        function getNumericValueLocal(val) {
            if (!val) return 0;
            return parseFloat(val.toString().replace(/\./g, '').replace(',', '.')) || 0;
        }
        
        $('.invoices-list-item').each(function() {
            let row = $(this);
            let price = getNumericValueLocal(row.find('.price-input').val());
            let qty = parseInt(row.find('.qty-input').val()) || 0;
            let subtotal = price * qty;
            row.find('.subtotal-input').val(subtotal.toLocaleString('id-ID', {minimumFractionDigits: 0}));
            row.find('.subtotal-input').data('value', subtotal);
        });
        
        // Let calculateGrandTotal be defined first, so we wait until it's defined to call it or just let the document input trigger it if needed. 
        // Wait, function declarations are hoisted, but calculateGrandTotal is declared inside document ready. Yes it hoisted to document ready scope.
        // It's safe to call it if we move the call after its declaration.
        
        function getNumericValue(val) {
            if (!val) return 0;
            // Remove group separator (dot) and replace radix point (comma) with dot
            return parseFloat(val.toString().replace(/\./g, '').replace(',', '.')) || 0;
        }

        function calculateRow(row) {
            let price = getNumericValue(row.find('.price-input').val());
            let qty = parseInt(row.find('.qty-input').val()) || 0;
            let subtotal = price * qty;
            row.find('.subtotal-input').val(subtotal.toLocaleString('id-ID', {minimumFractionDigits: 0}));
            row.find('.subtotal-input').data('value', subtotal);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let grandSubtotal = 0;
            $('.subtotal-input').each(function() {
                grandSubtotal += parseFloat($(this).data('value')) || 0;
            });

            let discount = getNumericValue($('#invoice-discount').val());
            let shippingCost = getNumericValue($('#shipping-cost').val());
            let grandTotal = grandSubtotal - discount + shippingCost;

            $('#grand-subtotal').text(grandSubtotal.toLocaleString('id-ID', {minimumFractionDigits: 0}));
            $('#grand-total').text(grandTotal.toLocaleString('id-ID', {minimumFractionDigits: 0}));
            $('#total-amount-hidden').val(grandTotal);
        }

        $('#add-item').click(function() {
            let newRow = $('.invoices-list-item').first().clone();
            
            // Remove existing select2 container if any
            newRow.find('.select2-container').remove();
            newRow.find('.select2').removeClass('select2-hidden-accessible');
            newRow.find('.select2').removeAttr('data-select2-id');
            newRow.find('.select2 option').removeAttr('data-select2-id');

            newRow.find('input').val('');
            newRow.find('.qty-input').val(1);
            newRow.find('.subtotal-input').val('0');
            
            // Update names
            let select = newRow.find('.product-select');
            select.attr('name', `items[${itemCount}][product_id]`).val('');
            let priceInput = newRow.find('.price-input');
            priceInput.attr('name', `items[${itemCount}][price]`).removeAttr('readonly');
            newRow.find('.type-select').attr('name', `items[${itemCount}][price_type]`);
            newRow.find('.qty-input').attr('name', `items[${itemCount}][qty]`);
            
            $('.invoices-list').append(newRow);
            
            // Re-initialize Select2 for the new row
            initSelect2(select);
            initMask();
            
            itemCount++;
        });

        $(document).on('click', '.remove-item', function() {
            if ($('.invoices-list-item').length > 1) {
                $(this).closest('tr').remove();
                calculateGrandTotal();
            }
        });

        $(document).on('change', '.product-select', function() {
            let selectedValue = $(this).val();
            let currentSelect = $(this);
            let isDuplicate = false;

            // Check if this product is already selected in other rows
            $('.product-select').not(currentSelect).each(function() {
                if ($(this).val() === selectedValue && selectedValue !== '') {
                    isDuplicate = true;
                    return false;
                }
            });

            if (isDuplicate) {
                Swal.fire({
                    title: 'Duplicate Product',
                    text: 'This product has already been added to the list.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                });
                $(this).val('').trigger('change.select2');
                return;
            }

            let row = $(this).closest('tr');
            let type = row.find('.type-select').val();
            let price = $(this).find(':selected').data('price-' + type.toLowerCase()) || 0;
            
            row.find('.price-input').val(price);
            initMask(); // Re-mask to format the new value
            calculateRow(row);
        });

        $(document).on('change', '.type-select', function() {
            let row = $(this).closest('tr');
            let type = $(this).val();
            let productSelect = row.find('.product-select');
            let price = productSelect.find(':selected').data('price-' + type.toLowerCase()) || 0;
            
            row.find('.price-input').val(price);
            initMask(); // Re-mask to format the new value
            calculateRow(row);
        });

        $(document).on('input', '.price-input, .qty-input', function() {
            calculateRow($(this).closest('tr'));
        });

        $(document).on('input', '#invoice-discount, #shipping-cost', function() {
            calculateGrandTotal();
        });

        // Initialize empty calculateGrandTotal if not calculated before
        calculateGrandTotal();
    });
</script>
@endpush
