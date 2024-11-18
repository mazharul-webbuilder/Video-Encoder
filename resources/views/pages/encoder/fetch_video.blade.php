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
                                    Progress
                                </th>
                                <th>
                                    Progress Rate
                                </th>
                                <th>
                                    Fetched At
                                </th>
                                <th>
                                    Status
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
                                            <div class="progress bg-gray-400" data-bs-toggle="tooltip" title="{{$video->progress}}%">
                                                <div class="progress-bar bg-primary"
                                                     role="progressbar"
                                                     style="width: {{$video->progress}}%"
                                                     aria-valuenow="{{$video->progress}}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{\Illuminate\Support\Number::percentage($video->progress)}}
                                        </td>
                                        <td>
                                            {{ $video->created_at->format('d-m-Y H:i:A') }}
                                        </td>
                                        <td>
                                            <button data-id="{{$video->id}}" type="button" class="retry btn {{$video->status == 'failed' ? 'btn-danger' : 'btn-primary'}}">
                                                {{strtoupper($video->status)}}
                                                {!!  $video->status == 'failed' ? "<span class='badge bg-success'> ( Retry )</span>" : '' !!}
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No video is encoding right now.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function () {
            setInterval(function () {
                fetchData();
            }, 15000); // 15 seconds interval

            function fetchData() {
                $.ajax({
                    url: '{{route('fetch.encoding.videos')}}',
                    type: 'GET',
                    success: function (data) {
                        updateTable(data);
                    }
                });
            }

            function updateTable(data) {
                // Clear existing table data
                $('#data-table tbody').empty();

                // Add new data to the table
                if(data.length > 0){
                    $.each(data, function (index, row) {
                        // Format the date using toLocaleString
                        let newRow = '<tr>' +
                            '<td>' + row.video_id + '</td>' +
                            '<td>' + row.file_name + '</td>' +
                            '<td>' +
                            '<div class="progress bg-gray-400" data-bs-toggle="tooltip" title="' + row.progress + '%">' +
                            '<div class="progress-bar bg-primary" role="progressbar" style="width: ' + row.progress + '%" aria-valuenow="' + row.progress + '" aria-valuemin="0" aria-valuemax="100">' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '<td>' + row.progress + '%' +'</td>' +
                            '<td>' + row.fetched_at + '</td>' +
                            '<td>' +
                            '<button data-id="'+ row.id +'" class="retry btn ' + (row.status == "failed" ? "btn-danger" : "btn-primary") + '">' +
                                row.status.toUpperCase() + (row.status == "failed" ? "<span class='badge bg-success'> ( Retry )</span>" : "") +
                            '</button>' +
                            '</td>'
                        '</tr>';
                        $('#data-table tbody').append(newRow);
                    });
                } else {
                    let row = '<tr><td colspan="5" class="text-center">No video is encoding right now.</td></tr>'
                    $('#data-table tbody').append(row);

                }
            }
        });
    </script>
    {{--Retry Failed Video--}}
    <script>
        $(document).ready(function () {
            $('body').on('click', '.retry', function () {
                const videoId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Encoding process will start from the beginning!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, retry it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('/retry-failed-video') }}/' + videoId,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            type: 'POST',
                            success: function (data) {
                                if (data.status) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: data.message
                                    });
                                    window.location.reload();
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: data.message
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });

    </script>
@endpush

