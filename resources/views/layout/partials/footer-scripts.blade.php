    <!-- jQuery -->
    <script src="{{URL::asset('build/js/jquery-3.7.1.min.js')}}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{URL::asset('build/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{URL::asset('build/js/moment.min.js')}}"></script>
	<script src="{{URL::asset('build/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Select2 JS -->
    <script src="{{URL::asset('build/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/flatpickr/flatpickr.min.js')}}"></script>

    <!-- Datatable JS -->
    <script src="{{URL::asset('build/plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/datatables/js/dataTables.bootstrap5.min.js')}}"></script>

    <!-- Mobile Input -->
    <script src="{{URL::asset('build/plugins/intltelinput/js/intlTelInput.js')}}"></script>
    <!-- Simplebar JS -->
	<script src="{{URL::asset('build/plugins/simplebar/simplebar.min.js')}}"></script>

    <!-- Main JS -->
    <script src="{{URL::asset('build/js/script.js')}}"></script>

    <!-- SweetAlert2 -->
    <script src="{{URL::asset('build/plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

    @if (Route::is(['users.index']))
        <script>
            window.usersDatatableUrl = "{{ route('users.datatables') }}";
        </script>
        <script src="{{URL::asset('build/json/manage-users-list.js')}}?v={{ time() }}"></script>
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(session('error_in_form') == 'edit')
                    const userId = "{{ session('edit_user_id') }}";
                    const offcanvasElement = document.getElementById('offcanvas_edit');
                    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
                    offcanvas.show();
                    $.ajax({
                        url: `/users/${userId}/edit`,
                        type: 'GET',
                        success: function (res) {
                            $('#offcanvas-edit-body').html(res);
                        }
                    });
                @else
                    const offcanvasElement = document.getElementById('offcanvas_add');
                    const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
                    offcanvas.show();
                @endif
            });
        </script>
        @endif
    @endif
    @if (Route::is(['roles.index']))
        <script>
            window.rolesDatatableUrl = "{{ route('roles.datatables') }}";
        </script>
        <script src="{{URL::asset('build/json/roles-list.js')}}?v={{ time() }}"></script>
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let modal = new bootstrap.Offcanvas(
                    document.getElementById('offcanvas_add')
                );
                modal.show();
            });
        </script>
        @endif
    @endif
    @if (Route::is(['permissions.index']))
        <script src="{{URL::asset('build/json/permission-list.js')}}?v={{ time() }}"></script>
    @endif

    {{-- //Contact Scripts Below --}}
    @if(Route::is(['contacts']))
        <script>
            window.contactsDatatableUrl = "{{ route('contacts.datatables') }}";
        </script>
        <script src="{{URL::asset('build/json/contact/contacts-list.js')}}?v={{ time() }}"></script>
    @endif

    {{-- //Custom Scripts Below --}}
    @if(Route::is(['customers']))
        <script>
            window.customersDatatableUrl = "{{ route('customers.datatables') }}";
        </script>
        <script src="{{URL::asset('build/json/customer/customers-list.js')}}?v={{ time() }}"></script>
    @endif



    @if(Route::is(['employees*']))
        <script>
            window.employeesDatatableUrl = "{{ route('employees.datatables') }}";
            window.employeesBaseUrl = "{{ url('employees') }}";
            window.csrfToken = "{{ csrf_token() }}";
        </script>
        <script src="{{URL::asset('build/json/employee/employees-list.js')}}?v={{ time() }}"></script>
    @endif

    @stack('scripts')

    <script>
        // Global Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.showToast = function(icon, title) {
            Toast.fire({
                icon: icon,
                title: title
            });
        };

        // Handle Laravel Session Flashes
        @if(session('success'))
            showToast('success', "{{ session('success') }}");
        @endif

        @if(session('error'))
            showToast('error', "{{ session('error') }}");
        @endif

        @if(session('warning'))
            showToast('warning', "{{ session('warning') }}");
        @endif

        @if(session('info'))
            showToast('info', "{{ session('info') }}");
        @endif

        @if ($errors->any())
            showToast('error', "{{ $errors->first() }}");
        @endif
    </script>
</body>
</html>
