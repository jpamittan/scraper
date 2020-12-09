@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-5 mb-5">
        <div class="table-responsive-md" style="width: 100%;">
            <form action="{{ route('home.processSnovIo') }}" method="post">
                @csrf
                <table class="table table-striped table-bordered">
                    <thead style="background-color: #1441a7; color: #FFFFFF;">
                        <tr>
                            <th scope="col"><input type='checkbox' id="checkAllheader"></th>
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
                        <?php $hasCheckList = false; ?>
                        @foreach ($company_names as $key => $company_name)
                            @foreach ($maps_results as $maps_result)
                                @if (array_key_exists('error', $maps_result))
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>{{ $company_name }}</td>
                                        <td colspan="11">
                                            <div class="alert alert-danger" role="alert">
                                                Blocked, cannot scrape information.
                                            </div>
                                        </td>
                                    </tr>
                                @elseif ($maps_result->query->q == $company_name)
                                    @if (property_exists($maps_result, 'maps_results'))
                                        @foreach ($maps_result->maps_results as $mr)
                                            <tr>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    @if (! empty($mr->url))
                                                        <?php $hasCheckList = true; ?>
                                                        <input type='checkbox' name='snovCheckList[]' class="checkSnov" value='{{ $ids["$key"] }} | {{ $company_name ?? $mr->title }} | {{ $mr->url ?? "" }}'>
                                                    @endif
                                                </td>
                                                <td>{{ $company_name }}</td>
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
                                            <!-- Get only first scrape result -->
                                            @break
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>{{ $company_name }}</td>
                                            <td colspan="11">
                                                <div class="alert alert-danger" role="alert">
                                                    No results found.
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    @continue
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot style="background-color: #1441a7; color: #FFFFFF;">
                        <tr>
                            <th scope="col"><input type='checkbox' id="checkAllFooter"></th>
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
                    </tfoot>
                </table>
                <div class="d-table mb-3">
                    <a href="{{ route('home.index') }}" class="btn btn-info"><i class="fab fa-searchengin mr-1"></i> Search again</a>
                    @if ($hasCheckList)
                        <button type="submit" class="btn btn-primary ml-1"><i class="fas fa-play mr-1"></i> Snov.io</button>
                    @else
                        <a href="{{ route('home.results') }}" class="btn btn-secondary ml-1"><i class="fas fa-poll mr-1"></i> Results</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#checkAllheader, #checkAllFooter').click(function() {
                if (
                    $('#checkAllheader').prop("checked") == true ||
                    $('#checkAllFooter').prop("checked") == true
                ) {
                    $(".checkSnov").prop("checked", true);
                } else {
                    $(".checkSnov").prop("checked", false);
                }
            });
        });
    </script>
@endpush
