@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Regional Data</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Provinces</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <form action="" method="GET">
                        <div class="input-icon input-icon-start position-relative">
                            <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" name="search" placeholder="Search Provinces..." value="{{ request('search') }}">
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
                            <th>Province Name</th>
                            <th>Total Regencies</th>
                            <th class="no-sort text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($provinces as $province)
                        <tr>
                            <td>{{ ($provinces->currentPage() - 1) * $provinces->perPage() + $loop->iteration }}</td>
                            <td>{{ $province->id }}</td>
                            <td>{{ $province->name }}</td>
                            <td>{{ $province->regencies_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('regional.regencies', $province->id) }}" class="btn btn-sm btn-outline-primary">
                                    View Regencies
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row align-items-center">
                <div class="col-md-12">
                     {{ $provinces->links() }}
                </div>
            </div>

        </div>

        @component('components.footer')
        @endcomponent

    </div>

@endsection
