{{-- <div class="d-flex gap-2">
    <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Cancel</a>
    <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" >
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-primary position-relative z-1 w-100" onclick="confirmDelete('delete-user-{{ $user->id }}')"  data-bs-dismiss="modal">Yes, Delete</button>
    </form>
</div> --}}
<div class="d-flex gap-3 mt-4">
    <form id="delete-user-{{ $user->id }}"
          action="{{ route('users.destroy', $user->id) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('DELETE')
        <button type="button"
            class="btn btn-light"
            data-bs-dismiss="modal">
        Cancel
    </button>
        <button type="submit"
                class="btn btn-danger">
            Yes, Delete
        </button>
    </form>
</div>


        {{-- <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Cancel</a>
        <a href="#" class="btn btn-primary position-relative z-1 w-100" data-bs-dismiss="modal">Yes, Delete</a> --}}

    <!-- delete modal -->
