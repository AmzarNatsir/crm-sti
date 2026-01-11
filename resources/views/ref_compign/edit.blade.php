@extends('layout.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Edit Campaign</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('ref-compign.index')}}">Campaigns</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-0 rounded-0">
                <form action="{{route('ref-compign.update', $compign->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Campaign Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{old('name', $compign->name)}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" value="{{old('start_date', $compign->start_date)}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" value="{{old('end_date', $compign->end_date)}}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Revenue</label>
                                    <input type="number" step="0.01" class="form-control" name="target_revenue" value="{{old('target_revenue', $compign->target_revenue)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Sales (Count)</label>
                                    <input type="number" class="form-control" name="target_sales" value="{{old('target_sales', $compign->target_sales)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Budget</label>
                                    <input type="number" step="0.01" class="form-control" name="badget" value="{{old('badget', $compign->badget)}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Channel</label>
                                    <input type="text" class="form-control" name="channel" value="{{old('channel', $compign->channel)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Target Segment</label>
                                    <input type="text" class="form-control" name="target_segment" value="{{old('target_segment', $compign->target_segment)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" {{old('status', $compign->status) == 'active' ? 'selected' : ''}}>Active</option>
                                        <option value="inactive" {{old('status', $compign->status) == 'inactive' ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{old('description', $compign->description)}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="2">{{old('notes', $compign->notes)}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{route('ref-compign.index')}}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Campaign</button>
                    </div>
                </form>
            </div>

        </div>

        @component('components.footer')
        @endcomponent

    </div>

@endsection
