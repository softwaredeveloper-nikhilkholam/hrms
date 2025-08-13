<!DOCTYPE html>
<html>
<head>
    <title>Resignation Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1>Dear Team,</h1>
    <p>This is to inform you that <b>{{ $data['employeeName'] }} (Employee ID: <b>{{ $data['empCode'] }}</b>), who is currently serving as <b>{{ $data['empDesignationName'] }}</b> in the <b>{{ $data['empDepartmentName'] }}</b>, has submitted their resignation on <b>{{ date('d-m-Y', strtotime($data['empResignationDate'])) }}</b>.</p>
    <b>Employee Details:</b>
    <ul>
        <li><b>Employee ID:</b> {{ $data['empCode'] }}</li>
        <li><b>Name:</b> {{ $data['employeeName'] }}</li>
        <li><b>Department:</b> {{ $data['empDepartmentName'] }}</li>
        <li><b>Designation:</b> {{ $data['empDesignationName'] }}</ li>
        <li><b>Reporting Authority:</b> {{ $data['empReportingAuthorityName'] }}</ li>
        <li><b>Resignation Date:</b> {{ date('d-m-Y', strtotime($data['empResignationDate'])) }}</ li>
    </ul>  
    <h5 style="color:red;">Note: This is a system-generated email. Please do not reply** to this message.</h5>

    <b>Regards,</b>  
    <b>Aaryans World School</b>  

</body>
</html>
