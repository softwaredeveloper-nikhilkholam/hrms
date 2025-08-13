<?php
 $username = Auth::user()->username;
 $userId = Auth::user()->id;
use App\Helpers\Utility;
$util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-7"><b style="color:red;">Active Product List</b></div>
                        <div  class="col-lg-5">
                            <a href="/product/create" class="btn mb-1 btn-primary">Add</a>
                            <a href="/product/dlist" class="btn mb-1 btn-primary">Deactive List <span class="badge badge-danger ml-2">{{$deactiveCount}}</span></a>
                            <a href="/product" class="btn mb-1 btn-success">Active List <span class="badge badge-danger ml-2">{{$activeCount}}</span></a>
                            <a href="/product/searchProduct" class="btn mb-1 btn-success" style="font-size: 14px !important;">
                                Print QR <span class="badge badge-danger ml-2"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                * {
                box-sizing: border-box;
                }

                /*the container must be positioned relative:*/
                .autocomplete {
                position: relative;
                display: inline-block;
                }

                input {
                border: 1px solid transparent;
                background-color: #f1f1f1;
                padding: 10px;
                font-size: 16px;
                }

                input[type=text] {
                background-color: #f1f1f1;
                width: 100%;
                }

                input[type=submit] {
                background-color: DodgerBlue;
                color: #fff;
                cursor: pointer;
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

                /*when hovering an item:*/
                .autocomplete-items div:hover {
                background-color: #e9e9e9; 
                }

                /*when navigating through the items using the arrow keys:*/
                .autocomplete-active {
                background-color: DodgerBlue !important; 
                color: #ffffff; 
                }
            </style>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-5"></div>
                            {!! Form::open(['action' => 'storeController\ProductsController@index', 'method' => 'GET', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                                <div  class="col-lg-7  d-flex">                                    
                                    <div class="autocomplete" style="width:900px;">
                                        <input id="myInput" type="text" value="{{$search}}" name="search" placeholder="Search...">
                                    </div>
                                    <button class="btn btn-danger" type="submit">Search</button>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            @if(count($products))
                                <table id="" data-page-length='25' class="table table-bordered table-striped" style="font-size:12px;">
                                    <thead>
                                        <tr class="ligth">
                                            <th style="font-size:13px;">No</th>
                                            <th style="font-size:13px;">Name</th>
                                            <th style="font-size:13px;">Category</th>
                                            <th style="font-size:13px;">Sub Category</th>
                                            <th style="font-size:13px;">Size</th>
                                            <th style="font-size:13px;">Color</th>
                                            <th style="font-size:13px;">Location <br> H/R/S</th>
                                            <th style="font-size:13px;">Stock</th>
                                            <th style="font-size:13px;">Action<?php $i=1; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $row)
                                        @php  $stock = $util->getCurrentProductStock($row->id); @endphp
                                            <tr>
                                                <td style="padding: 0px 17px !important;">{{$i++}}</td>
                                                <td style="padding: 0px 17px !important;" class="text-left">{{ucfirst($row->name)}}</td>
                                                <td style="padding: 0px 17px !important;">{{ucfirst($row->categoryName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{ucfirst($row->subCategoryName)}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->size}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->color}}</td>
                                                <td style="padding: 0px 17px !important;">{{$row->hallName}} / {{$row->rackName}} / {{$row->shelfName}}</td>
                                                <td style="padding: 0px 17px !important;">{{$stock}} <br>[Rs. {{$util->numberFormatRound($stock*$row->productRate)}}]</td>
                                                <td style="padding: 0px 17px !important;">
                                                    <div class="d-flex align-items-center list-action">
                                                        <a class="btn btn-primary btn-sm mr-2" href="/product/{{$row->id}}"><i class="fa fa-eye"></i></a>
                                                        <a class="btn btn-success  btn-sm mr-2" href="/product/{{$row->id}}/edit"><i class="fa fa-pencil mr-0"></i></a>
                                                        <a class="btn btn-warning  btn-sm mr-2" href="/product/{{$row->id}}/deactivate"><i class="fa fa-trash mr-0"></i></a>
                                                        <a class="btn btn-danger  btn-sm mr-2" href="/produdcts/printQRCode/{{$row->id}}" target="_blank">QR</a>
                                                        <a class="btn btn-primary" href='/reports/openingStockReport?startDate={{$row->openingStockForDate}}&endDate={{date('Y-m-d')}}&productId={{$row->id}}' target="_blank">Ledger</a>
                                                        @if($userId == 4506)
                                                            <a class="btn btn-danger  btn-sm mr-2" style="background-color: purple !important;" href="/product/changeOpeningStock/{{$row->id}}" target="_blank">Opening Stock</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'>{{$products->links()}}</div>
                                    <div class='col-md-4'><a class="btn btn-danger"  href="/produdcts/exportExcelSheet/{{($search != '')?$search:'-'}}/1" target="_blank">Export Excel</a></div>
                                </div>
                            @else
                                <h4>Record not found</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua & Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre & Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts & Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Turks & Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("myInput"), countries);
</script>
