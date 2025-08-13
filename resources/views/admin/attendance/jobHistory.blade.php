@extends('layouts.master')
@section('title', 'Final Attendance Management')
@section('content')
    <div class="container-fluid">
        {{-- Page Header and Filter Form --}}
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title" style="font-weight: 600;">Confirm Sheet List</h4>
            </div>
            <div class="page-rightheader">
                <a class="btn btn-success" href="/empAttendances/confirmSheetList">Confirm Sheet List</a>
                <a class="btn btn-primary" href="/empAttendances/finalAttendanceSheet">Final Sheet</a>
            </div>
        </div>
        <div class="card" style="box-shadow: 0 4px 15px rgba(0,0,0,0.07); border: none; border-radius: 10px; margin-bottom: 1.5rem;">
            <div class="card-body">
                {!! Form::open(['url' => route('finalSheet.confirmSheetList'), 'method' => 'GET']) !!}
                    <div class="row align-items-end">
                        <div class="col-md-3 form-group"></div>
                        <div class="col-md-2 form-group">
                            <label>Month <span class="text-danger">*</span></label>
                            <input type="month" class="form-control" name="forMonth" value="{{ $forMonth ?? '' }}" required>
                        </div>
                        <div class="col-md-1 form-group">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search mr-1"></i> Search</button>
                        </div>
                        <div class="col-md-3 form-group"></div>
                    </div>
                {!! Form::close() !!}   
                @if($jobs)
                    <div class="table-responsive">
                        <table class="table  table-vcenter text-nowrap table-bordered border-top border-bottom" id="hr-table">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" width="10%">Sr No.</th>
                                    <th class="border-bottom-0" width="20%">Branch</th>
                                    <th class="border-bottom-0" width="20%">Section</th>
                                    <th class="border-bottom-0" width="20%">HR Department</th>
                                    <th class="border-bottom-0" width="15%">Higher Authority</th>
                                    <th class="border-bottom-0" width="15%">Accounts Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Ascon</td>
                                    <td>Teaching</td>
                                    <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>Ascon</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>3</td>
                                    <td>Bhilarewadi</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>Bhilarewadi</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>5</td>
                                    <td>Dattanagar</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>6</td>
                                    <td>Dattanagar</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>7</td>
                                    <td>Warje</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>8</td>
                                    <td>Warje</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>9</td>
                                    <td>Bibvewadi</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>10</td>
                                    <td>Bibvewadi</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>11</td>
                                    <td>Manajinagar</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>12</td>
                                    <td>Manajinagar</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>13</td>
                                    <td>Dhayari</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>14</td>
                                    <td>Dhayari</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>15</td>
                                    <td>Gokulnagar</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>16</td>
                                    <td>Gokulnagar</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>17</td>
                                    <td>Solapur</td>
                                    <td>Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>18</td>
                                    <td>Solapur</td>
                                    <td>Non Teaching</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                     <td>✔<br>5-08-2025 4pm</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
