<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\TicketExport;
use App\Ticket;
use App\EmpMr;
use App\LetterHead;
use App\CommonForm;
use App\User;
use PDF;
use Auth;
use DB;
use Excel;

class TicketsController extends Controller
{
    public function index()
    {
        return view('admin.tickets.list');
    }

    public function raiseTicket($flag)
    {
        return view('admin.tickets.create')->with(['flag'=>$flag]);
    }

    public function list()
    {
        // return Auth::user()->empId;
        $tickets = Ticket::where('empId', Auth::user()->empId)->orderBy('status')
        ->orderBy('updated_at', 'desc')
        ->paginate(10);
        return view('admin.tickets.ticketList')->with(['tickets'=>$tickets]);
    }
  
    public function store(Request $request)
    {
        // return $request->all();
        // Wrap the logic in a try-catch block for error handling
        try {
        //     // First, validate the incoming request data.
            $validatedData = $request->validate([
                'flag' => 'required|integer', // Changed 'issueType' to 'flag' to match the form
                'issue' => 'required',
                'note' => 'nullable|string',
                'fromMonth' => 'nullable|string', // Added validation for fromMonth
            ]);

            // Your conditional check for issueType and the closing date.
            if ($validatedData['flag'] == 1) {
                // Assuming CommonForm model exists with an 'active' and 'lastDateFillArrears' field
                $lastDateFillArrears = CommonForm::where('active', 1)->value('lastDateFillArrears');
                if (date('Y-m-d') > $lastDateFillArrears) {
                    return redirect()->back()->withInput()->with("error", "Arrears filling Date closed...");
                }
            }

            // Create a new Ticket instance and populate the data
            $ticket = new Ticket;
            $ticket->empId = Auth::user()->empId;
            $ticket->issueType = $validatedData['flag']; // Changed to use the 'flag' field
            $ticket->issue = $validatedData['issue'];
            $ticket->note = $validatedData['note'];
            $ticket->fromMonth = $validatedData['fromMonth']; // Use the value from the form
            if ($validatedData['flag'] != 1) 
                $ticket->period = $validatedData['period'];
            $ticket->ticketMonth = date('Y-m');
            $ticket->updated_by = Auth::user()->username;
            $ticket->save();

            // Redirect with a success message upon successful ticket creation
            return redirect('/tickets/list')->with("success", "Ticket Raised successfully.");

        } catch (\Exception $e) {
            // Catch any exceptions and redirect back with a generic error message
            // In a real application, you might want to log the specific error
            return redirect()->back()->withInput()->with("error", "An error occurred while raising the ticket. Please try again.");
        }
    }


    public function show($id)
    {
        $ticket = Ticket::find($id);
        return view('admin.tickets.show')->with(['ticket'=>$ticket]);
    }

    public function allTickets(Request $request)
    {
        $year = $request->year;

        $issueType = $request->issueType;
        if($year == '')
            $year = date('Y');
        
        $userType = Auth::user()->userType;
        $tickets = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.name', 'emp_dets.empCode','designations.name as designationName', 'contactus_land_pages.branchName','tickets.*')
        ->whereIn('tickets.status', [1, 4, 5]);
        if($userType == '61')
            $tickets = $tickets->whereIn('tickets.issue', ['FORM 16', 'SALARY CERTIFICATE']);

        if($issueType != '')
        {
            $tickets = $tickets->where('tickets.issueType', $issueType);
        }
       
        $tickets = $tickets->orderBy('tickets.created_at','desc')
        ->orderBy('tickets.status','desc')
        ->get();
        return view('admin.tickets.allticketList')->with(['tickets'=>$tickets, 'year'=>$year, 'issueType'=>$issueType]);
    }

    public function changeStatus($id)
    {
        $ticket = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.name', 'emp_dets.empCode','designations.name as designationName', 'contactus_land_pages.branchName', 'tickets.*')
        ->where('tickets.id', $id)
        ->first();
        $fromMonth = $toMonth = '';
        if($ticket->issueType == 3)
        {
            if($ticket->period == 'Last Month')
                $fromMonth = date('Y-m', strtotime('-1 month', strtotime($ticket->created_at)));
            elseif($ticket->period == 'Last 3 Months')
                $fromMonth = date('Y-m', strtotime('-3 month', strtotime($ticket->created_at)));
            elseif($ticket->period == 'Last 6 Months')
                $fromMonth = date('Y-m', strtotime('-6 month', strtotime($ticket->created_at)));
            elseif($ticket->period == 'Last 9 Months')
                $fromMonth = date('Y-m', strtotime('-9 month', strtotime($ticket->created_at)));
            elseif($ticket->period == 'Last 12 Months')
                $fromMonth = date('Y-m', strtotime('-12 month', strtotime($ticket->created_at)));
            else
                $fromMonth = date('Y-m', strtotime('-1 month', strtotime($ticket->created_at)));

            $toMonth = date('Y-m', strtotime('-1 month'));
        }
        $letterHeads = LetterHead::pluck('name', 'id');
        return view('admin.tickets.ChangeStatus')->with(['letterHeads'=>$letterHeads,'ticket'=>$ticket, 'fromMonth'=>$fromMonth, 'toMonth'=>$toMonth]);
    }

    public function updateStatus(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->remark = $request->remark;
        if(isset($request->onHold))
            $ticket->status = $request->onHold;

        if(isset($request->approved))
            $ticket->status = $request->approved;
 
        if(isset($request->rejected))
            $ticket->status = $request->rejected;

        if(isset($request->inprogress))
            $ticket->status = $request->inprogress;
            

        if(!empty($request->file))
        {
            $fileName = time().'.'.$request->file->getClientOriginalExtension();  
            $request->file->move(public_path('/admin/requestedDocs/'), $fileName);
            $ticket->fileName = $fileName;
        }

        $ticket->letterHeadId = $request->letterHeadId;
        if($request->letterHeadId != '')
            $ticket->signId = 10;

        if($request->letterHeadId!= '')
        $ticket->fromMonth = $request->fromMonth;
        $ticket->toMonth = $request->toMonth;
        $ticket->arrearsDays = $request->arrearsDays;
        $ticket->updated_by = Auth::user()->username;
        $ticket->save();
        return redirect('/tickets/allTickets')->with("success","Ticket Updated successfully..");
    }


    public function downloadSalaryCertificate($id, $startMonth, $endMonth)
    {
        $empDet = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.name', 'emp_dets.jobJoingDate','emp_dets.salaryScale','emp_dets.empCode',
        'designations.name as designationName', 'tickets.*')
        ->where('tickets.id', $id)
        ->first();

        $salary = EmpMr::where('empId', $empDet->empId)
        ->where('forDate', '>=', $startMonth)
        ->where('forDate', '<=', $endMonth)
        ->orderBy('forDate')
        ->get();

        $letterHead = LetterHead::find($empDet->letterHeadId);

        $file = 'Salary_Certificate.pdf';
        $pdf = PDF::loadView('admin.pdfs.salaryCertificate', compact('empDet', 'salary','letterHead'));
        return $pdf->stream($file);  

    }

    public function archiveTicketList(Request $request)
    {
        $year = $request->year;
        if($year == '')
            $year = date('Y');

        if(Auth::user()->userType == '51' || Auth::user()->userType == '61')
        {
            $tickets = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.name', 'emp_dets.empCode','designations.name as designationName', 'tickets.*')
            ->whereIn('tickets.status', [2,3])
            ->whereYear('tickets.created_at', date('Y'));
            if(Auth::user()->userType == '61')
                $tickets = $tickets->whereIn('tickets.issue', ['FORM 16', 'SALARY CERTIFICATE']);

            $tickets = $tickets->orderBy('tickets.updated_at','desc')->get();
        }
        else
        {
            $tickets = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_dets.name', 'emp_dets.empCode','designations.name as designationName', 'tickets.*')
            ->whereIn('tickets.status', [2,3])
            ->whereYear('tickets.created_at', date('Y'))
            ->where('tickets.empId', Auth::user()->empId)
            ->orderBy('tickets.updated_at','desc')
            ->get();
        }

        return view('admin.tickets.archiveticketList')->with(['tickets'=>$tickets, 'year'=>$year]);
    }

    public function exportExcel($year, $type)
    {
        $fileName = 'Tickets.xlsx';
        return Excel::download(new TicketExport($year, $type), $fileName);
    }

    public function deactivate($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return redirect()->back()->with("error", "Ticket not found.");
        }

        try {
            $ticket->status = 3;
            $ticket->updated_by = Auth::user()->username;
            $ticket->save();

            return redirect()->back()->with("success", "Ticket deactivated successfully.");
        } catch (\Exception $e) {
            // Log the error in a real application
            return redirect()->back()->with("error", "An error occurred while deactivating the ticket. Please try again.");
        }
    }
}
