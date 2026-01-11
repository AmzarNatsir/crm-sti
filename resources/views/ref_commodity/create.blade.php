@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content pb-0">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Add New Commodity</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('ref-commodity.index')}}">Commodities</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add New</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <div class="card-body">
                    @include('ref_commodity.partials.form')
                </div>
            </div>

        </div>

        @component('components.footer')
        @endcomponent

    </div>

@endsection
