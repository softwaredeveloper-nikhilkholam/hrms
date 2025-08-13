<?php
    $user = Auth::user();
    $userType = $user->userType;
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <center><img src="/2023.jpg" height="550" width="900"></img><br>
    @if($userType == '11' || $userType == '21' || $userType == '31')
        <h2><a href="/dashboard">Skip....</a></h2>
    @else
        <h2><a href="/home">Skip....</a></h2>
    @endif
    </center>
</body>
</html>