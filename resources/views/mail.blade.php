@if($flag == 'newEmployee')
    <h3><b style="color:red;">{{ $name }}</b>, Welcome to the Aaryans World School Family.</h3>
    <p>This is your Details</p>
    <hr>
    <p>Employee Login Details :</p>
    <p>Username : {{$username}}</p>
    <p>Password : {{$password}}</p>
    <a href="http://202.94.174.221/">Click Here.....</a>
    <hr>
@endif