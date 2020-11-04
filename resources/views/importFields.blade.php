@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-5">
        <div class="card" style="overflow: auto;">
            <div class="card-body">
                <h5 class="card-title"><b>Select search keywords for company name</b></h5>
                <p class="card-text">
                    <form class="form-horizontal" method="POST" action="{{ route('home.processImport') }}">
                        {{ csrf_field() }}
                        <table class="table">
                            @foreach ($csv_data as $row)
                                <tr>
                                @foreach ($row as $key => $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                @foreach ($csv_data[0] as $key => $value)
                                    <td>
                                        <select name="fields[{{ $key }}]">
                                            @foreach (config('app.db_fields') as $db_field)
                                                <option value="{{ $loop->index }}">{{ $db_field }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search-location"></i> Search
                        </button>
                    </form>
                </p>
            </div>
        </div>
    </div>
@endsection
