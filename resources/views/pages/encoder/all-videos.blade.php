@extends('layout.master')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Encoding Videos</li>
        </ol>
    </nav>
    {{--St--}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Encoding Videos</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    S/L
                                </th>
                                <th>
                                    Video Id
                                </th>
                                <th>
                                    File Name
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Fetched At
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($videos as $video)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $video->video_id }}
                                    </td>
                                    <td>
                                        {{ getHumanReadableFilename($video->file_name) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm bg-light border-1 border-info">
                                            {{ucfirst($video->status)}}
                                        </button>
                                    </td>
                                    <td>
                                        {{ $video->created_at->format('d-m-Y H:i:A') }}
                                    </td>
                                    <td>
                                        @if($video->deleted_at)
                                            <button type="button" class="restore-btn btn btn-secondary" data-id="{{$video->id}}">
                                                Restore
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            {{$videos->links()}}
    </div>

@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function () {
            $('.restore-btn').click(function () {
                const videoId = $(this).data('id')
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('restore-soft-deleted-video') }}' + '/' + videoId,
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
                                } else if(data.error) {
                                    Toast.fire({
                                        icon: 'error',
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
