<table width="100%">
    <thead>
        <tr>
            <th width="10%">Emp Code</th>
            <th>Name</th>
            <th width="10%">Branch</th>
            <th width="10%">Department</th>
            <th width="10%">Designation</th>
            <th width="10%">Joining Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $emp)
            <tr>
                <td>{{ $emp->empCode }}</td>
                <td>{{ $emp->name }}</td>
                <td>{{ $emp->branchName }}</td>
                <td>{{ $emp->departmentName }}</td>
                <td>{{ $emp->designationName }}</td>
                <td>{{ \Carbon\Carbon::parse($emp->jobJoingDate)->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
