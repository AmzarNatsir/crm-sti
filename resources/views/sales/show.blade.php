@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <div class="row">
                <div class="col-lg-10 mx-auto">

                    <h6 class="mb-3 fs-14"> <a href="{{route('sales.index')}}"><i class="ti ti-arrow-left me-1"></i>Sales List</a></h6>

                    <div class="card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <img src="{{URL::asset('build/img/logo_app.svg')}}" class="invoice-light-logo" width="150" alt="logo">
                                <span class="badge bg-info-subtle text-info-emphasis fs-14"> #{{ $order->invoice_no }}</span>
                            </div>

                            <!-- Details -->
                            <div class="row pb-3 border-bottom mb-4">
                                <div class="col-lg-4">
                                    <h5 class="mb-2 fs-16 fw-bold"> Invoice Information </h5>
                                    <p class="text-body mb-1"> Invoice No : <span class="text-dark fw-medium"> {{ $order->invoice_no }}</span> </p>
                                    <p class="text-body mb-1"> Date : <span class="text-dark fw-medium"> {{ $order->invoice_date->format('d M Y') }}</span> </p>
                                    <p class="text-body mb-1"> Payment Method : <span class="text-dark fw-medium"> {{ $order->paymentMethod->name ?? 'N/A' }}</span> </p>
                                    @if($order->compaign_id)
                                    <p class="text-body mb-1"> Campaign : <span class="text-dark fw-medium"> {{ $order->campaign->name ?? 'N/A' }}</span> </p>
                                    @endif
                                    <p class="text-body mb-0"> Status : <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger' }}">{{ strtoupper($order->payment_status) }}</span> </p>
                                </div>
                                <div class="col-lg-4">
                                </div>
                                <div class="col-lg-4 text-lg-end">
                                    <h5 class="mb-2 fs-16 fw-bold"> Bill To </h5>
                                    <p class="text-dark fw-bold mb-1"> {{ $order->customer->name ?? 'N/A' }} </p>
                                    <p class="text-body mb-1"> {{ $order->customer->company_name ?? '' }} </p>
                                    <p class="text-body mb-0"> {{ $order->customer->email ?? '' }} </p>
                                </div>
                            </div>

                            <!-- Items -->
                            <div class="mb-4">
                                <h6 class="mb-3 fs-16 fw-bold"> Order Items </h6>
                                <div class="table-responsive">
                                    <table class="table table-nowrap border">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Product</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                                                <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                                <td class="text-center">{{ $item->qty }}</td>
                                                <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="row pb-3 mb-3 border-bottom">
                                <div class="col-lg-7">
                                </div>
                                <div class="col-lg-5">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fs-14 fw-medium text-body">Subtotal</h6>
                                        <h6 class="fs-14 fw-semibold text-dark">{{ number_format($order->total_amount + $order->invoice_discount, 2) }}</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                                        <h6 class="fs-14 fw-medium text-body">Discount</h6>
                                        <h6 class="fs-14 fw-semibold text-danger">-{{ number_format($order->invoice_discount, 2) }}</h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="fs-18 fw-bold">Total</h6>
                                        <h6 class="fs-18 fw-bold text-primary">{{ number_format($order->total_amount, 2) }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-dark me-2" onclick="window.print()"> <i class="ti ti-printer me-1"></i> Print</button>
                                <a href="{{ route('sales.index') }}" class="btn btn-primary">Back to List</a>
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
