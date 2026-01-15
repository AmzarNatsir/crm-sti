<form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Photo Section -->
        <div class="col-md-12 mb-3">
            <div class="d-flex align-items-center flex-column">
                <div class="mb-2">
                    <img src="https://ui-avatars.com/api/?name=New+Employee&background=random&size=128" 
                         id="photo-preview" 
                         alt="Preview" 
                         class="rounded-circle border" 
                         style="width: 128px; height: 128px; object-fit: cover;">
                </div>
                <div class="input-group">
                    <input type="file" name="photo" class="form-control" id="photo-input" accept="image/*">
                </div>
                <small class="text-muted mt-1">Image file only (jpeg, png, jpg, gif, webp, svg). Max 2MB.</small>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Employee Number <span class="text-danger">*</span></label>
            <input type="text" name="employee_number" class="form-control" required placeholder="EMP001">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Identity Number <span class="text-danger">*</span></label>
            <input type="text" name="identitiy_number" class="form-control" required placeholder="NIK">
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="Full Name">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required placeholder="email@example.com">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" placeholder="Phone Number">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Birth Date</label>
            <input type="date" name="birth_date" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Place of Birth</label>
            <input type="text" name="place_of_birth" class="form-control" placeholder="Place of Birth">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Last Education</label>
            <select name="last_education" class="form-select">
                <option value="">Select Education</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="D3">D3</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Position</label>
            <select name="positionId" class="form-select">
                <option value="">Select Position</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Join Date</label>
            <input type="date" name="join_date" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Salary</label>
            <input type="number" name="salary" class="form-control" value="0" placeholder="0">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="resigned">Resigned</option>
            </select>
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Full Address"></textarea>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Employee</button>
    </div>
</form>

<script>
    // Photo preview and validation logic
    document.getElementById('photo-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('photo-preview');
        const defaultAvatar = 'https://ui-avatars.com/api/?name=New+Employee&background=random&size=128';

        if (file) {
            // Check if file is an image
            if (!file.type.startsWith('image/')) {
                if (window.showToast) {
                    window.showToast('error', 'Please select a valid image file (jpeg, png, webp, etc.)');
                } else {
                    alert('Please select a valid image file.');
                }
                this.value = ''; // Clear the input
                preview.src = defaultAvatar; // Reset preview
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = defaultAvatar;
        }
    });
</script>
