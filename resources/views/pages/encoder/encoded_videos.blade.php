@extends('layout.master')
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Encoded Videos</li>
        </ol>
    </nav>
    {{--St--}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Encoded Videos</h4>
                    <div class="table-responsive">
                        <table class="table table-striped" id="data-table">
                            <thead>
                            <tr>
                                <th>
                                    Video Id
                                </th>
                                <th>
                                    File Name
                                </th>
                                <th>
                                    Video Formats
                                </th>
                                <th>
                                    Destination Path
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($videos) > 0)
                                @foreach($videos as $video)
                                    <tr>
                                        <td>
                                            {{ $video->video_id }}
                                        </td>
                                        <td>
                                            {{ getHumanReadableFilename($video->file_name) }}
                                        </td>
                                        <td>
                                            {{ implode(', ', json_decode($video->video_formats)) }}
                                        </td>
                                        <td>
                                            {{ $video->destination_path }}
                                        </td>
                                        <td>
                                            <button type="button"  data-id="{{$video->id}}" class="btn btn-warning btn-sm deleteBtn">Soft Delete</button>
                                            <button type="button"
                                                    data-id="{{$video->id}}"
                                                    class="btn btn-danger btn-sm force-delete">Force Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No video encoded yet.!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="">{{$videos->links()}}</div>
    </div>

@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function () {
            /*Soft Delete*/
            $('.deleteBtn').click(function () {
                const videoId = $(this).data('id')
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, soft delete it!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('/soft-delete-video') }}' + '/' + videoId,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            type: 'POST',
                            success: function (data) {
                                if (data.message) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.message
                                    })
                                    window.location.reload();
                                } else if (data.error){
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.error
                                    })
                                }
                            }
                        })
                    }
                })
            })
            /*Delete Permanently*/
            $('.force-delete').click(function () {
                const videoId = $(this).data('id')
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete permanently!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('/force-delete') }}' + '/' + videoId,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            type: 'POST',
                            success: function (data) {
                                if (data.message) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.message
                                    })
                                    window.location.reload();
                                } else if (data.error){
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.error
                                    })
                                }
                            }
                        })
                    }
                })
            })
        });
    </script>
@endpush

