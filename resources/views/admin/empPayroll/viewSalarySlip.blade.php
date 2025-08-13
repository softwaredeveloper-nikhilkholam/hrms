<?php
    use App\Helpers\Utility;
    $util = new Utility();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .payslip-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8">

    <div class="max-w-4xl mx-auto">
        <!-- Print Button -->
        <div class="mb-4 text-right no-print">
            <a href="/empPayroll/raiseReqSalarySlip" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors duration-300"><- Back</a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                Print Payslip
            </button>
        </div>

        <!-- Payslip Container -->
        <div class="bg-white rounded-lg shadow-lg p-6 sm:p-10 border border-gray-200 payslip-container">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row justify-between items-start pb-6 border-b border-gray-200" style="padding: 30px !important;">
                <div class="flex items-center">
                    <img src="https://hrms.aaryansworld.com/landingpage/images/logo.png" alt="Aaryans World School Logo" style="padding-right:5px;" width="170">
                    <div style="padding-right:10px;">
                        <h3 class="text-xl sm:text-1xl font-bold text-gray-800"><center>{{$empDet->organisationName}}</center></h3>
                        <p class="text-sm text-gray-500"><center>Aaryans World School</center></p>
                        <p class="text-xs text-gray-500 mt-1"><center>New Pune - Satara bypass highway, Near Navle bridge, Narhe, Pune-411041.</center></p>
                        <p class="text-xs text-gray-500 mt-1"><center>Payslip For the month of {{date('M-Y', strtotime($salDet->month))}}</center></p>
                    </div>
                </div>
            </header>
            <!-- Employee Details -->
            <section class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Employee Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Name:</span> <span class="text-gray-800">{{$empDet->name}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Effective Work Days:</span> <span class="text-gray-800">{{number_format($salDet->presentDays, 1)}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Join Date:</span> <span class="text-gray-800">{{date('d-m-Y', strtotime($empDet->jobJoingDate))}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Days In Month:</span> <span class="text-gray-800">{{$daysInMonth = date('t', strtotime($salDet->month))}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Designation:</span> <span class="text-gray-800">{{$empDet->desName}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Gross Salary:</span> <span class="text-gray-800">₹ {{number_format($salDet->grossSalary)}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Department:</span> <span class="text-gray-800">{{$empDet->deptName}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Location:</span> <span class="text-gray-800">{{$empDet->branchName}}</span></div>
                </div>
            </section>

            <!-- Earnings and Deductions -->
            <section class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Emoluments -->
                    <div>
                        <h3 class="text-lg font-semibold text-green-700 bg-green-50 p-3 rounded-t-lg">Emoluments</h3>
                        <div class="border-l border-r border-b border-gray-200 rounded-b-lg p-4 space-y-2">
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Basic Salary (BS) & HRA</span> <span class="font-medium text-gray-800">₹ {{number_format($salDet->presentDays*($salDet->grossSalary / $daysInMonth))}}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Extra Working (EW)</span> <span class="font-medium text-gray-800">₹ {{($salDet->extraWorkDays)?number_format($salDet->extraWorkDays*($salDet->grossSalary / $daysInMonth)):0}}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Transportation Allowance (TA)</span> <span class="font-medium text-gray-800">₹ {{number_format($salDet->transportAllowance)}}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Mobile/Tel. Allowance (MA)</span> <span class="font-medium text-gray-800">₹ {{number_format($salDet->otherAllowance)}}</span></div>
                        </div>
                    </div>
                    <!-- Deductions -->
                    <div>
                        <h3 class="text-lg font-semibold text-red-700 bg-red-50 p-3 rounded-t-lg">Deductions</h3>
                        <div class="border-l border-r border-b border-gray-200 rounded-b-lg p-4 space-y-2">
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Professional Tax (PT)</span> <span class="font-medium text-gray-800">₹  {{number_format($salDet->PT) }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Tax Deducted at Source (TDS)</span> <span class="font-medium text-gray-800">₹ {{number_format($salDet->TDS) }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Other Deductions (Loan, etc)</span> <span class="font-medium text-gray-800">₹ {{number_format($salDet->advanceAgainstSalary+$salDet->otherDeduction+$salDet->retention)}}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-600">Provident Fund</span> <span class="font-medium text-gray-800">₹ {{number_format($salDet->PF) }}</span></div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Totals -->
            <section class="mt-6 bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center sm:text-left">
                    <div>
                        <p class="text-sm text-gray-500">Gross Pay</p>
                        <p class="text-lg font-semibold text-gray-800">₹ {{number_format($grossPay = ( ($salDet->presentDays)?($salDet->presentDays*($salDet->grossSalary / $daysInMonth)):0 ) + ($salDet->extraWorkDays*($salDet->grossSalary / $daysInMonth)) + $salDet->transportAllowance + $salDet->otherAllowance )}}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Deductions</p>
                        <p class="text-lg font-semibold text-red-600">₹ {{number_format($totalDeduction = $salDet->PT + $salDet->TDS + $salDet->PT + $salDet->advanceAgainstSalary + $salDet->otherDeduction + $salDet->PF + $salDet->retention)}}</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-sm text-gray-500">Net Pay</p>
                        <p class="text-xl font-bold text-blue-600">₹ {{ number_format($grossPay - $totalDeduction) }}</p>
                    </div>
                </div>
                <div class="text-center sm:text-right mt-2 text-sm font-medium text-gray-600">
                    (Rupees {{$util->numberToWord($grossPay - $totalDeduction)}} only)
                </div>
            </section>

            <!-- Bank & Statutory Details -->
            <section class="mt-8">
                 <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Financial & Statutory Information</h3>
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Bank Name:</span> <span class="text-gray-800">{{$salDet->bankName}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">PF UAN No:</span> <span class="text-gray-800">NA</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">Bank Account No.:</span> <span class="text-gray-800">{{$salDet->bankAccountNo}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">ESI No.:</span> <span class="text-gray-800">NA</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">IFSC Code:</span> <span class="text-gray-800">{{$salDet->bankIFSCCode}}</span></div>
                    <div class="flex justify-between"><span class="font-medium text-gray-600">PAN No.:</span> <span class="text-gray-800">{{$empDet->PANNo}}</span></div>
                     <div class="flex justify-between"><span class="font-medium text-gray-600">Aadhar No.:</span> <span class="text-gray-800">{{$empDet->AADHARNo}}</span></div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="mt-10 pt-6 border-t border-dashed border-gray-300 text-center">
                <p class="text-xs text-gray-500">This is a system-generated payslip and does not require a signature.</p>
            </footer>
        </div>
    </div>
</body>
</html>
