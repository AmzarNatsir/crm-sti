<!-- Edit Product -->
<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this, 'product-image-edit-preview')">
                    <div class="mt-2 text-center">
                        @if($product->image)
                            <img id="product-image-edit-preview" src="{{ asset('storage/' . $product->image) }}" alt="Product Image" style="max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                        @else
                            <img id="product-image-edit-preview" src="#" alt="Preview" style="display:none; max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                        @endif
                    </div>
                </div>
            </div>
    <script>
        if (typeof previewImage !== 'function') {
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    </script>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="type_id" required>
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ $product->type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Merk <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="merk_id" required>
                        <option value="">Select Merk</option>
                        @foreach($merks as $merk)
                            <option value="{{ $merk->id }}" {{ $product->merk_id == $merk->id ? 'selected' : '' }}>{{ $merk->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
             <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Price CS <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="price_cs" value="{{ $product->price_cs ? (int)$product->price_cs : '' }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Price R1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="price_r1" value="{{ $product->price_r1 ? (int)$product->price_r1 : '' }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Price R2 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="price_r2" value="{{ $product->price_r2 ? (int)$product->price_r2 : '' }}" required>
                </div>
            </div>
             <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Price Farmer Group</label>
                    <input type="text" class="form-control" name="price_fg" value="{{ $product->price_fg ? (int)$product->price_fg : '' }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Margin</label>
                    <input type="text" class="form-control" name="margin" value="{{ $product->margin ? str_replace('.', ',', $product->margin) : '' }}">
                </div>
            </div>
             <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="is_active">
                        <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end">
        <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
