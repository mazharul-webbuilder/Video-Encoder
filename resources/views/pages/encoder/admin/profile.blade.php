@extends('layout.master')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Profile</li>
        </ol>
    </nav>
    {{--St--}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" id="AdminProfileUpdateForm">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Username</label>
                            <input type="text" readonly class="form-control" id="exampleInputUsername1" autocomplete="off"  value="{{$admin->username}}" onclick="alert('Username is not editable')">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Name</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" value="{{$admin->name}}" readonly onclick="alert('Name is not editable')">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Current Password*</label>
                            <input type="password" name="current_password" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="Enter current password">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">New Password*</label>
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="Enter new password">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Retype New Password*</label>
                            <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="Retype new password">
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('custom-scripts')
    {{--Toast Notification--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
        })

    </script>
    <script>
        $('#AdminProfileUpdateForm').on('submit', function (e) {
            e.preventDefault();
            const AdminProfileUpdateForm = $(this);
            // Clear previous error messages
            $('.error-message').remove();

            // Serialize the form data
            const formData = AdminProfileUpdateForm.serialize();

            $.ajax({
                url: '{{ route('admin.profile.update') }}',
                type: 'POST',
                data: formData,
                dataType: 'json', // Expect JSON response from the server
                // JavaScript to hide the modal after a successful operation
                success: function (data) {
                    if (data.response === 200) {
                        // Reset the form fields
                        $('#AdminProfileUpdateForm')[0].reset();

                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        // Display error messages for each input field
                        $.each(errors, function (field, errorMessage) {
                            const inputField = $('[name="' + field + '"]');
                            inputField.after('<span class="error-message text-danger">' + errorMessage[0] + '</span>');
                        });
                    } else {
                        console.log('An error occurred:', status, error);
                    }
                }
            });
        });
    </script>
@endpush
