@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-5 mb-5">
        <div class="table-responsive-md" style="width: 100%;">
            <table class="table table-striped table-bordered">
                <thead style="background-color: #1441a7; color: #FFFFFF;">
                    <tr>
                        <th scope="col">Company Name</th>
                        <th scope="col">Title</th>
                        <th scope="col">URL</th>
                        <th scope="col">Address</th>
                        <th scope="col">Parse Address</th>
                        <th scope="col">Stars</th>
                        <th scope="col">Reviews</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Hours</th>
                        <th scope="col">Type</th>
                        <th scope="col">Latitude</th>
                        <th scope="col">Longitude</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <?php
                            $json_data = json_decode($result->json_data);
                        ?>
                        @if (property_exists($json_data, 'maps_results'))
                            @foreach ($json_data->maps_results as $mr)
                                <tr>
                                    <td>{{ $result->company_name }}</td>
                                    <td>{{ $mr->title ?? "" }}</td>
                                    <td>{{ $mr->url ?? "" }}</td>
                                    <td>{{ $mr->address ?? "" }}</td>
                                    <td>{{ $mr->directions->address_parsed ?? "" }}</td>
                                    <td>{{ $mr->stars ?? "" }}</td>
                                    <td>{{ $mr->reviews ?? "" }}</td>
                                    <td>{{ $mr->phone ?? "" }}</td>
                                    <td>{{ $mr->hours ?? "" }}</td>
                                    <td>{{ $mr->type ?? "" }}</td>
                                    <td>{{ $mr->coordinates->latitude ?? "" }}</td>
                                    <td>{{ $mr->coordinates->longitude ?? "" }}</td>
                                </tr>
                                @if (!empty($mr->url))
                                    @foreach ($snov_data as $snov)
                                        @if ($result->company_name == $snov->company_name)
                                            <tr>
                                                <td colspan="12">
                                                    <div class="accordion" id="accordion-{{ $snov->id }}">
                                                        <div class="card bg-info">
                                                            <div class="card-header" id="heading-{{ $snov->id }}">
                                                                <h2 class="mb-0">
                                                                    <button class="btn btn-link btn-no-glow text-dark" type="button" data-toggle="collapse" data-target="#collapse-{{ $snov->id }}" aria-expanded="true" aria-controls="collapse-{{ $snov->id }}">
                                                                        Snov.io Results <i class="fas fa-angle-down"></i>
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                            <div id="collapse-{{ $snov->id }}" class="collapse" aria-labelledby="heading-{{ $snov->id }}" data-parent="#accordion-{{ $snov->id }}">
                                                                <div class="card-body bg-white">
                                                                    <?php
                                                                        $snovResult = json_decode($snov->snov_data);
                                                                    ?>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                            <th scope="col">Email</th>
                                                                            <th scope="col">Status</th>
                                                                            <th scope="col">First Name</th>
                                                                            <th scope="col">Last Name</th>
                                                                            <th scope="col">Position</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($snovResult->emails as $email)
                                                                                <tr>
                                                                                    <td>{{ $email->email ?? "" }}</td>
                                                                                    <td>{{ $email->status ?? "" }}</td>
                                                                                    <td>{{ $email->firstName ?? "" }}</td>
                                                                                    <td>{{ $email->lastName ?? "" }}</td>
                                                                                    <td>{{ $email->position ?? "" }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @break
                            @endforeach
                        @else
                            <tr>
                                <td>{{ $result->company_name }}</td>
                                <td colspan="11">
                                    <div class="alert alert-danger" role="alert">
                                        No results found.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="d-table mb-3">
                <a href="{{ route('home.index') }}" class="btn btn-info"><i class="fab fa-searchengin mr-1"></i> Search again</a>
                <a href="{{ route('export.export') }}" class="btn btn-success ml-1"><i class="far fa-file-excel mr-1"></i> Export</a>
            </div>
        </div>
    </div>
@endsection
