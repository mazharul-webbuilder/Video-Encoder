@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-8 ps-md-0 mx-auto">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2">Durbar<span>Tech</span></a>
              <h5 class="text-muted fw-normal mb-4">Admin Login</h5>
              <form class="forms-sample" id="LoginForm">
                  @csrf
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="password" autocomplete="current-password" placeholder="Password">
                </div>
                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" id="authCheck">
                  <label class="form-check-label" for="authCheck">
                    Remember me
                  </label>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">Login</button>
                </div>
              </form>
            </div>
          </div>
        </div>
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
        $(document).ready(function () {
            $('#LoginForm').on('submit', function (e) {
                e.preventDefault();
                const LoginForm = $(this);
                // Clear previous error messages
                $('.error-message').remove();

                // Serialize the form data
                const formData = LoginForm.serialize();

                $.ajax({
                    url: '{{ route('admin.login.post') }}',
                    type: 'POST',
                    data: formData,
                    dataType: 'json', // Expect JSON response from the server
                    success: function (data) {
                        if (data.response === 401) {
                            // Reset the form fields
                            $('#LoginForm')[0].reset();

                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                        } else if(data.response === 200){
                            Toast.fire({
                                icon: 'success',
                                title: 'Welcome to Admin Dashboard'
                            });
                            window.location = data.route
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
        })
    </script>
@endpush
