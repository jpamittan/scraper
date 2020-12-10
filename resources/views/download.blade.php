@extends('layouts.master')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="table-responsive-md" style="width: 100%;">
            <table class="table table-striped table-bordered">
                <thead style="background-color: #1441a7; color: #FFFFFF;">
                    <tr>
                        <th scope="col">List Name</th>
                        <th scope="col">Created At</th>
                        <th scope="col">No of Records</th>
                        <th scope="col">Status</th>
                        <th scope="col">Completion</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listnames as $data)
                        <tr>
                            <th scope="row">{{ $data->name }}</th>
                            <td>{{ $data->created_at }}</td>
                            <td>{{ $data->row_count }}</td>
                            <td>
                                @if ($data->status == "progress")
                                    Scraping 
                                    <div class="spinner-border spinner-border-sm text-warning" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                @elseif ($data->status == "done")
                                    Done <i class="far fa-check-circle text-success"></i>
                                @else
                                    $data->status
                                @endif
                            </td>
                            <td>
                                @if ($data->status == "progress")
                                    <div class="progress">
                                        <div
                                            class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                            role="progressbar"
                                            aria-valuenow="{{ ($data->done / 100) * $data->row_count }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{ ($data->done / 100) * $data->row_count }}%"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="{{ $data->done }}/{{ $data->row_count }}"
                                        ></div>
                                    </div>
                                @elseif ($data->status == "done")
                                    <div class="progress">
                                        <div
                                            class="progress-bar bg-success"
                                            role="progressbar"
                                            aria-valuenow="100"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: 100%"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="{{ $data->done }}/{{ $data->row_count }}"
                                        ></div>
                                    </div>
                                @else
                                    <div class="progress">
                                        <div
                                            class="progress-bar bg-danger"
                                            role="progressbar"
                                            aria-valuenow="100"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: 100%"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="{{ $data->done }}/{{ $data->row_count }}"
                                        ></div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($data->status == "done")
                                    <a href="{{ route('export.export', ['id' => $data->id]) }}"><i class="far fa-file-excel"></i> Download</a>
                                @else
                                    Please wait...
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {!! $listnames->links() !!}
            </div>
        </div>
    </div>
@endsection
