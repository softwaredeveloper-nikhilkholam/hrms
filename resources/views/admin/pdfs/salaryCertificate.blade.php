@php ($fontPath = storage_path('fonts/gargi.ttf')) @endphp
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: "gargi";
            src: url("{{ $fontPath }}") format('truetype');
        }

        @page {
            margin: 0cm 0cm;
        }

        body {
            margin: 0cm 0cm;
            font-family: gargi, DejaVu Sans, "Times New Roman", serif;
        }

        .letterhead {
            width: 100%;
            height: 180px;
            display: block;
            margin-bottom:0px !important;
        }

        .content {
            margin-top:0px !important;
            padding: 2cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 8px;
            font-size: 10px;
            border: 1px solid black;
            text-align: center;
        }

        .no-border td, .no-border th {
            border: none;
            text-align: left;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .subtitle {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }

        .section {
            margin-top: 20px;
            font-size: 14px;
        }

        .footer-note {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: gray;
            font-style: italic;
        }
    </style>
</head>

<body>
    <?php use App\Helpers\Utility; $util = new Utility(); ?>

    {{-- Letterhead (edge to edge) --}}
    <img src="{{ public_path('admin/letterHeads/' . ($letterHead->fileName ?? 'ellor.jpg')) }}" class="letterhead" />

    <div class="content">
        <div class="title">SALARY CERTIFICATE</div>
        <div class="subtitle">TO WHOMSOEVER IT MAY CONCERN</div>

        <div class="section">
            This is to certify that <b>Mr/Ms/Mrs. {{ $empDet->name }}</b>, Emp Code <b>{{ $empDet->empCode }}</b>,
            was working with us as <b>“{{ $empDet->designationName }}”</b> since
            <b>{{ date('d-m-Y', strtotime($empDet->jobJoingDate)) }}</b> till date.
        </div>

        <div class="section">
            His/Her salary for the academic year {{ date('Y') }} - {{ date('Y', strtotime('+1 year')) }}
            is ₹{{ $util->numberFormatRound($empDet->salaryScale) }}/- per month.
        </div>

        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th width="11%">Month</th>
                        <th>Present Days</th>
                        <th>Gross Salary</th>
                        <th>Addition / Allowances</th>
                        <th>Deduction / Advances</th>
                        <th>Retention</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salary as $key => $value)
                        <?php
                            $perDay = $value->grossSalary / date('t', strtotime($value->forDate));
                            $retention = $util->getRetention($value->empId, $value->forDate);
                            if($retention == '') {
                                $retention = 0;
                            }
                            $deductions = $value->WF + $value->PT + $value->PF + $value->ESIC + $value->MLWL +
                                          $value->advanceAgainstSalary + $value->otherDeduction;
                            $gross = $perDay * $value->totalDays;
                            $allowances = $value->extraWorking * $perDay;
                            $net = $allowances + $gross - ($deductions + $retention);
                        ?>
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ date('M-Y', strtotime($value->forDate)) }}</td>
                            <td>{{ $value->totalDays }}</td>
                            <td>{{ $util->numberFormatRound($gross) }}</td>
                            <td>{{ $util->numberFormatRound($allowances) }}</td>
                            <td>{{ $util->numberFormatRound($deductions) }}</td>
                            <td>{{ $util->numberFormatRound($retention) }}</td>
                            <td>{{ $util->numberFormatRound($net) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            This certificate is issued on his/her own written request.
        </div>

        <?php $signDetials = $util->getSignatureDetail($empDet->signId); ?>
        @if($signDetials)
            <div class="section" style="margin-top: 20px;">
                <div><b>Thanking you</b></div>
                <img src="{{ public_path('admin/signFiles/' . $signDetials->fileName) }}"
                     style="width: 180px; height: 90px;">
                <div><b>{{ $signDetials->name }}</b></div>
                <div><b>{{ $signDetials->designationName }}</b></div>
                <div><b>Aaryans World School</b></div>
            </div>
        @endif
    </div>

    <div class="footer-note">
        <b>Note:</b> This is a system-generated letter. No signature is required.
    </div>
</body>
</html>
