@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Regional Data - {{ $district->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('regional.index') }}">Provinces</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('regional.regencies', $district->regency->province_id) }}">
                                    {{ $district->regency->province->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('regional.districts', $district->regency_id) }}">
                                    {{ $district->regency->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $district->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <form action="" method="GET">
                        <div class="input-icon input-icon-start position-relative">
                            <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" name="search" placeholder="Search Villages..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table List -->
            <div class="table-responsive table-nowrap custom-table mb-3">
                <table class="table table-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Village Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($villages as $village)
                        <tr>
                            <td>{{ ($villages->currentPage() - 1) * $villages->perPage() + $loop->iteration }}</td>
                            <td>{{ $village->id }}</td>
                            <td>{{ $village->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row align-items-center">
                <div class="col-md-12">
                     {{ $villages->links() }}
                </div>
            </div>

        </div>

        @component('components.footer')
        @endcomponent

    </div>

@endsection
