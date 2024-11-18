@extends('layout.master')
@push('plugin-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush
@push('style')
    <style>
        thead, tbody, tfoot, tr, td, th {
            border-color: inherit;
            border-style: none;
            border-width: 0;
        }

        td {
            padding: 0.85rem 0 !important;
        }

        /*.dataTables_wrapper .dataTables_filter {*/
        /*    float: left;*/
        /*    text-align: right;*/
        /*    margin-bottom: 12px;*/
        /*}*/
    </style>
@endpush
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Encoding List</li>
        </ol>
    </nav>
    {{--St--}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="py-2" style="width: 95%">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <h4 class="card-title">Encoding List</h4>
                            </div>
                            <div class="col-md-auto">
                                <button type="button"
                                        id="encodingButton"
                                        class="btn btn-primary disabled"
                                        data-bs-toggle="modal"
                                        data-bs-target="#myModal">
                                    Encode
                                </button>
                                <input type="hidden" id="controlEncodingButton" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="Datatable">
                            <thead>
                            <tr>
                                <th>
                                    Video Id
                                </th>
                                <th>
                                    File Name
                                </th>
                                <th class="text-center">
                                    Action
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.encoder._modal')

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
    {{--Select 2--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{--Datatable--}}
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    {{--Load Datatable--}}
    @include('pages.encoder._script')
@endpush
