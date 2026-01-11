<form action="{{ isset($commodity) ? route('ref-commodity.update', $commodity->id) : route('ref-commodity.store') }}" method="POST">
    @csrf
    @if(isset($commodity))
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Commodity Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $commodity->name ?? '' }}" placeholder="Enter commodity name" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Season (Month)</label>
            <input type="number" name="season" class="form-control" value="{{ $commodity->season ?? '' }}" placeholder="Enter season in days">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fertilization In Season</label>
            <input type="number" name="fertillization_in_season" class="form-control" value="{{ $commodity->fertillization_in_season ?? '' }}" placeholder="Enter fertilization count">
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Enter description">{{ $commodity->description ?? '' }}</textarea>
        </div>
    </div>
    <div class="text-end border-top pt-3">
        <button type="button" class="btn btn-light me-1" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>
