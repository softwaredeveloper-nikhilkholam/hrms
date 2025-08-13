<?php

namespace App\Exports;

use App\Cif3Application;
use App\Interview;
use App\Designation;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CandidateBankExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    private $page;
    private $applicationType;
    private $status;
    private $fromDate;
    private $toDate;
    private $rowCount;

    public function __construct($page, $applicationType, $status, $fromDate, $toDate, $rowCount)
    {
        $this->page = $page;
        $this->applicationType = $applicationType;
        $this->status = $status;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->rowCount = $rowCount;
    }

    public function columnFormats(): array
    {
        return [
            'AC' => NumberFormat::FORMAT_NUMBER
        ];
    }


    public function collection()
    {
        $page=$this->page;
        $applicationType=$this->applicationType;
        $status=$this->status;
        $fromDate=$this->fromDate;
        $toDate=$this->toDate;
        $rowCount=$this->rowCount;

        if($applicationType == 0)
        {
            $applications = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
            ->join('designations', 'cif3_applications.designationId', 'designations.id')
            ->select('departments.name as departmentName','designations.name as designationName',
                'cif3_applications.*')
            ->where('cif3_applications.appType', $applicationType)
            ->where('cif3_applications.status', 1)
            ->whereDate('cif3_applications.created_at', '>=', $fromDate)
            ->whereDate('cif3_applications.created_at', '<=', $toDate);
        }
        else
        {
            $applications = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
            ->join('designations', 'cif3_applications.designationId', 'designations.id')
            ->select('departments.name as departmentName','designations.name as designationName',
            'cif3_applications.*')
            ->where('cif3_applications.appType', $applicationType)
            ->where('cif3_applications.status', 1)
            ->where('cif3_applications.forDate', '>=', $fromDate)
            ->where('cif3_applications.forDate', '<=', $toDate);
        }

        if($status == 'CBC' || $status == 'Rejected' || $status == 'Selected')
            $applications =$applications->where('cif3_applications.appStatus', $status);
        else
            $status=0;
        
        $applications =$applications->get(); 
        $this->rowCount = count($applications);

        $temp=$apps=[];
        $k=1;
        foreach($applications as $app)
        {
            $temp['no'] = $k++;
            $temp['id'] = $app->id;
            $temp['forDate'] =  date('d-m-Y', strtotime($app->created_at));
            $temp['name'] =  $app->firstName.' '.$app->middleName.' '.$app->lastName;
            $temp['department'] = $app->departmentName;
            $temp['designation'] = $app->designationName;
            $temp['mobileNo'] = $app->mobileNo;
            $temp['email'] = $app->email;
            

            $interview1 = Interview::join('users', 'interviews.assignTo', 'users.id')
            ->select('interviews.*','users.name')
            ->where('interviews.candidateId', $app->id)
            ->where('interviews.round', 1)
            ->where('interviews.active', 1)
            ->first();
            if($interview1)
            {
                $temp['round_1'] = "1";
                $temp['rating1_1'] = $interview1->rating1;
                $temp['rating2_1'] = $interview1->rating2;
                $temp['rating3_1'] = $interview1->rating3;
                $temp['rating4_1'] = $interview1->rating4;
                $temp['rating5_1'] = $interview1->rating5;
                $temp['rating6_1'] = $interview1->rating6;
                $temp['expectedSalary_1'] = $interview1->expectedSalary;
                $temp['postOffered_1'] = Designation::where('id', $interview1->postOffered)->value('name');
                $temp['offeredSalary_1'] = $interview1->offeredSalary;
                $temp['appStatus_1'] = $interview1->appStatus;
                $temp['remark_1'] = $interview1->remarks;
            }
            else
            {
                $temp['round_1'] = "";
                $temp['rating1_1'] = "";
                $temp['rating2_1'] = "";
                $temp['rating3_1'] = "";
                $temp['rating4_1'] = "";
                $temp['rating5_1'] = "";
                $temp['rating6_1'] = "";
                $temp['expectedSalary_1'] = "";
                $temp['postOffered_1'] = "";
                $temp['offeredSalary_1'] = "";
                $temp['appStatus_1'] = "";
                $temp['remark_1'] = "";
            }

            $interview2 = Interview::join('users', 'interviews.assignTo', 'users.id')
            ->select('interviews.*','users.name')
            ->where('interviews.candidateId', $app->id)
            ->where('interviews.round', 2)
            ->where('interviews.active', 1)
            ->first();

            if($interview2)
            {
                $temp['round_2'] = "2";
                $temp['demoDate_2'] = $interview2->demoDate;
                $temp['branchId_2'] = ($interview2->branchId != NULL)?ContactusLandPage::where('id', $interview2->branchId)->value('branchName'):'' ;
                $temp['subject_2'] = $interview2->subject;
                $temp['standard_2'] = $interview2->standard;
                $temp['topic_2'] = $interview2->topic;
                $temp['videoLink_2'] = $interview2->videoLink;
                $temp['nameOfObserver_2'] = $interview2->nameOfObserver;
                $temp['remarks_2'] = $interview2->remarks;
                $temp['recomandation_2'] = $interview2->recomandation;
                $temp['appStatus_2'] = $interview2->appStatus;
            }
            else
            {
                $temp['round_2'] = "";
                $temp['demoDate_2'] = "";
                $temp['branchId_2'] = "";
                $temp['subject_2'] = "";
                $temp['standard_2'] = "";
                $temp['topic_2'] = "";
                $temp['videoLink_2'] = "";
                $temp['nameOfObserver_2'] = "";
                $temp['remarks_2'] = "";
                $temp['recomandation_2'] = "";
                $temp['appStatus_2'] = "";
            }

            $interview3 = Interview::join('users', 'interviews.assignTo', 'users.id')
            ->select('interviews.*','users.name')
            ->where('interviews.candidateId', $app->id)
            ->where('interviews.round', 3)
            ->where('interviews.active', 1)
            ->first();

            if($interview3)
            {
                $temp['round_3'] = "3";
                $temp['rating1_3'] = $interview3->rating1;
                $temp['rating2_3'] = $interview3->rating2;
                $temp['rating3_3'] = $interview3->rating3;
                $temp['rating4_3'] = $interview3->rating4;
                $temp['rating5_3'] = $interview3->rating5;
                $temp['rating6_3'] = $interview3->rating6;
                $temp['remarks_3'] = $interview3->remarks;
                $temp['expectedSalary_3'] = $interview3->expectedSalary;
                $temp['postOffered_3'] = Designation::where('id', $interview3->postOffered)->value('name');
                $temp['offeredSalary_3'] = $interview3->offeredSalary;
                $temp['appStatus_3'] = $interview3->appStatus;
            }
            else
            {
                $temp['round_3'] = "3";
                $temp['rating1_3'] = "";
                $temp['rating2_3'] = "";
                $temp['rating3_3'] = "";
                $temp['rating4_3'] = "";
                $temp['rating5_3'] = "";
                $temp['rating6_3'] = "";
                $temp['remarks_3'] = "";
                $temp['expectedSalary_3'] = "";
                $temp['postOffered_3'] = "";
                $temp['offeredSalary_3'] = "";
                $temp['appStatus_3'] = "";
            }

            $interview4 = Interview::join('users', 'interviews.assignTo', 'users.id')
            ->select('interviews.*','users.name')
            ->where('interviews.candidateId', $app->id)
            ->where('interviews.round', 4)
            ->where('interviews.active', 1)
            ->first();

            if($interview4)
            {
                $temp['round_4'] = "4";
                $temp['branchId_4'] = $interview4->branchId;
                $temp['postOffered_4'] = Designation::where('id', $interview4->postOffered)->value('name');
                $temp['sectionSelectedFor_4'] = $interview4->sectionSelectedFor;
                $temp['subjectSelectedFor_4'] = $interview4->subjectSelectedFor;
                $temp['dateOfJoining_4'] = ($interview4->dateOfJoining == NULL)?'':date('d-m-Y', strtotime($interview4->dateOfJoining));
                $temp['reportingAuthId_4'] = $interview4->reportingAuthId;
                $temp['mentorBuddy_4'] = $interview4->mentorBuddy;
                $temp['timing_4'] = $interview4->timing;
                $temp['salary_4'] = $interview4->salary;
                $temp['hikeComment_4'] = $interview4->hikeComment;
                $temp['appStatus_4'] = $interview4->appStatus;
                $temp['remarks_4'] = $interview4->remarks;
            }
            else
            {
                $temp['round_4'] = "4";
                $temp['branchId_4'] = "";
                $temp['postOffered_4'] = "";
                $temp['sectionSelectedFor_4'] = "";
                $temp['subjectSelectedFor_4'] = "";
                $temp['dateOfJoining_4'] = "";
                $temp['reportingAuthId_4'] = "";
                $temp['mentorBuddy_4'] = "";
                $temp['timing_4'] = "";
                $temp['salary_4'] = "";
                $temp['hikeComment_4'] = "";
                $temp['appStatus_4'] = "";
                $temp['remarks_4'] = "";
            }

            array_push($apps, $temp);
        }

        return $published_goals = collect($apps);
    }

    public function headings(): array
    {
        return ["Sr. No.","Recruitement Id","Date", "Candidate Name","Department", "Designation Applied for",  "Mobile No.", "email Id", 
        "Round 1", "Eligibility", "Smartness", "Knowledge", "Appearance", "English Fluency", "Confidence", "Expected Salary", "Post Offered", "Offered Salary", "Application Status","Remark",
        "Round 2","Date of Demo", "Branch", "Subject", "Standard", "Topic", "Video Link","Name of the observer","Remark of the observer","Recomandation","Application Status",
        "Round 3", "Eligibility", "Smartness", "Knowledge", "Appearance", "English Fluency", "Confidence", "Remarks", "Expected Salary", "Post Offered", "Offered Salary", "Application Status",
        "Round 4","Selected Branch", "Post Offered", "Section Selected for", "Subject Selected for", "Date of Joining", "Select Reporting Authority", "Mentor / Buddy", "Timing","Final Salary","Hike in Salary - Commitments if any", "Application Status","Remarks"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:BD1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');

                $event->sheet->getDelegate()->freezePane('A2');

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:BD1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                
                $cells = 'A1:BE'.($this->rowCount+1);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('k')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('W')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('X')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Y')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Z')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AA')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AB')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AC')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AD')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AE')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AF')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AG')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AH')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AI')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AJ')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Ak')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AL')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AM')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AN')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AO')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AP')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AQ')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AR')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AS')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AT')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AU')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AV')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AW')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AX')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AY')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AZ')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('BA')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('BB')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('BC')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('BD')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('W')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('X')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Y')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Z')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AA')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AB')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AC')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AD')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AE')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AF')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AG')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AH')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AI')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AJ')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AK')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AL')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AM')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AN')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AO')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AP')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AR')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AS')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AT')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AU')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AV')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AW')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AX')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AY')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AZ')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('BA')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('BB')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('BC')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('BD')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getColumnDimension('P')->setAutoSize(true);
                $event->sheet->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getColumnDimension('R')->setAutoSize(true);
                $event->sheet->getColumnDimension('S')->setAutoSize(true);
                $event->sheet->getColumnDimension('T')->setAutoSize(true);
                $event->sheet->getColumnDimension('U')->setAutoSize(true);
                $event->sheet->getColumnDimension('V')->setAutoSize(true);
                $event->sheet->getColumnDimension('W')->setAutoSize(true);
                $event->sheet->getColumnDimension('X')->setAutoSize(true);
                $event->sheet->getColumnDimension('Y')->setAutoSize(true);
                $event->sheet->getColumnDimension('Z')->setAutoSize(true);
                $event->sheet->getColumnDimension('AA')->setAutoSize(true);
                $event->sheet->getColumnDimension('AB')->setAutoSize(true);
                $event->sheet->getColumnDimension('AC')->setAutoSize(true);
                $event->sheet->getColumnDimension('AD')->setAutoSize(true);
                $event->sheet->getColumnDimension('AE')->setAutoSize(true);
                $event->sheet->getColumnDimension('AF')->setAutoSize(true);
                $event->sheet->getColumnDimension('AG')->setAutoSize(true);
                $event->sheet->getColumnDimension('AH')->setAutoSize(true);
                $event->sheet->getColumnDimension('AI')->setAutoSize(true);
                $event->sheet->getColumnDimension('AJ')->setAutoSize(true);
                $event->sheet->getColumnDimension('AK')->setAutoSize(true);
                $event->sheet->getColumnDimension('AK')->setAutoSize(true);
                $event->sheet->getColumnDimension('AL')->setAutoSize(true);
                $event->sheet->getColumnDimension('AM')->setAutoSize(true);
                $event->sheet->getColumnDimension('AN')->setAutoSize(true);
                $event->sheet->getColumnDimension('AO')->setAutoSize(true);
                $event->sheet->getColumnDimension('AP')->setAutoSize(true);
                $event->sheet->getColumnDimension('AQ')->setAutoSize(true);
                $event->sheet->getColumnDimension('AR')->setAutoSize(true);
                $event->sheet->getColumnDimension('AS')->setAutoSize(true);
                $event->sheet->getColumnDimension('AT')->setAutoSize(true);
                $event->sheet->getColumnDimension('AU')->setAutoSize(true);
                $event->sheet->getColumnDimension('AV')->setAutoSize(true);
                $event->sheet->getColumnDimension('AW')->setAutoSize(true);
                $event->sheet->getColumnDimension('AX')->setAutoSize(true);
                $event->sheet->getColumnDimension('AY')->setAutoSize(true);
                $event->sheet->getColumnDimension('AZ')->setAutoSize(true);
                $event->sheet->getColumnDimension('BA')->setAutoSize(true);
                $event->sheet->getColumnDimension('BB')->setAutoSize(true);
                $event->sheet->getColumnDimension('BC')->setAutoSize(true);
                $event->sheet->getColumnDimension('BD')->setAutoSize(true);
            },
        ];
    }
}
