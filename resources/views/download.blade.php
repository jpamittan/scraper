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
                            <td class="text-center">
                                @if ($data->status == "done")
                                    <a href="{{ route('export.export', ['listname' => $data->id]) }}" class="text-decoration-none ml-1 mr-1" title="Download">
                                        <i class="fas fa-file-download text-primary"></i>
                                    </a>
                                    <a href="#" data-href="{{ route('home.delete', ['listname' => $data->id]) }}" class="text-decoration-none ml-1 mr-1" title="Delete" data-toggle="modal" data-target="#confirm-delete">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
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
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <b>Please confirm</b>
                </div>
                <div class="modal-body">
                    Are you sure to delete the record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>
@endpush
