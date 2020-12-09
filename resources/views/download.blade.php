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
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Test name</td>
                        <td>2020-09-30 17:44:25</td>
                        <td>1000</td>
                        <td>
                            <div class="progress">
                                <div 
                                    class="progress-bar progress-bar-striped progress-bar-animated bg-warning" 
                                    role="progressbar" 
                                    aria-valuenow="75" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100" 
                                    style="width: 75%"
                                    data-toggle="tooltip" 
                                    data-placement="top"
                                    title="755/1000"
                                ></div>
                            </div>
                        </td>
                        <td>
                            Download
                        </td>
                    </tr>
                    <tr>
                        <td>Test name 2</td>
                        <td>2020-09-30 14:12:12</td>
                        <td>5000</td>
                        <td>
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
                                    title="5000/5000"
                                ></div>
                            </div>
                        </td>
                        <td>
                            Download
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
