<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Store & Purchase Mgmt.</title>
<link rel="shortcut icon" href="https://templates.iqonic.design/posdash/html/assets/images/favicon.ico" />
<link rel="stylesheet" href="{{asset('storeAdmin/css/backend-plugin.min.css')}}">
<link rel="stylesheet" href="{{asset('storeAdmin/css/backende209.css?v=1.0.0')}}">
<link rel="stylesheet" href="{{asset('storeAdmin/vendor/%40fortawesome/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('storeAdmin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('storeAdmin/vendor/remixicon/fonts/remixicon.css')}}"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<style>
    * { box-sizing: border-box; }

table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    text-align: center;
}

.autocomplete {
/*the container must be positioned relative:*/
position: relative;

}
input {
border: 1px solid transparent;
background-color: red;
padding: 10px;
font-size: 16px;
}

label{
    font-size:12px !important;
    font-weight: bold !important;
}

input[type=submit] {
background-color: DodgerBlue;
color: #fff;
}

.autocomplete-items {
position: absolute;
border: 1px solid #d4d4d4;
border-bottom: none;
border-top: none;
z-index: 99;
/*position the autocomplete items to be the same width as the container:*/
top: 100%;
left: 0;
right: 0;
}
.autocomplete-items div {
padding: 10px;
cursor: pointer;
background-color: #fff;
border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
/*when hovering an item:*/
background-color: #e9e9e9;
}
.autocomplete-active {
/*when navigating through the items using the arrow keys:*/
background-color: DodgerBlue !important;
color: #ffffff;
}

.btn-success, .bg-success {
    font-size: 14px !important;
    background-color: #007839 !important;
    border-color: #007839 !important;
    color: white !important;
}

.btn-danger, .bg-danger {
    font-size: 14px !important;
    background-color: #ff0000 !important;
    border-color: #ff0000 !important;  
    color: white !important;  
}

.btn-primary, .bg-primary {
    font-size: 14px !important;
    background-color: #4432ea !important;
    border-color: #4432ea !important;
    color: white !important;
}

.form-control
{
    font-size:13px !important;
    height: 35px !important;
}

</style>