<table class="table table-bordered">
    <thead>
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
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Position</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
            <?php $json_data = json_decode($result->json_data); ?>
            @if (property_exists($json_data, 'maps_results'))
                @foreach ($json_data->maps_results as $mr)
                    @if (!empty($mr->url))
                        <?php $snov_data = json_decode($result->snov_data); ?>
                        @if (!empty($snov_data->emails))
                            @foreach ($snov_data->emails as $email)
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
                                    <td>{{ $email->email ?? "" }}</td>
                                    <td>{{ $email->status ?? "" }}</td>
                                    <td>{{ $email->firstName ?? "" }}</td>
                                    <td>{{ $email->lastName ?? "" }}</td>
                                    <td>{{ $email->position ?? "" }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    @break
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>