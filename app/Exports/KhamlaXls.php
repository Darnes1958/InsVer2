<?php
namespace App\Exports;


use App\Models\aksat\main_view;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\Customers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;


class KhamlaXls extends DefaultValueBinder implements FromCollection,WithMapping, WithHeadings,
                            WithEvents,WithColumnWidths,WithStyles,WithColumnFormatting,WithCustomValueBinder
 {
    public $bank_name;
    public $ByTajmeehy;
    public $TajNo;
    public $bank_no;
    public $months;
    public $RepRadio;
    public $rowcount;
    public $sul;
    public $sul_pay;
    public $raseed;


    /**
     * @return array
     */
    public function __construct(string $ByTajmeehy,int $TajNo,int $bank,int $months,string $repradio,string $bank_name)
    {
        $this->ByTajmeehy=$ByTajmeehy;
        $this->TajNo=$TajNo;
        $this->bank_no = $bank;
        $this->months = $months;
        $this->RepRadio = $repradio;
        $this->bank_name = $bank_name;
    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event)  {
                $event->sheet
                    ->getStyle('A8:K8')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('E8E1E1');

                $event->sheet->getDelegate()->getStyle('B')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('D')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('J')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
              $event->sheet->getDelegate()->getStyle('K')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                if ($this->RepRadio=='RepAll')
                 $event->sheet->setCellValue('D6', 'كشف بالعقود الخاملة لمدة '.$this->months.'  شهور .. بتاريخ '.date('Y-m-d'));
                if ($this->RepRadio=='RepSome')
                 $event->sheet->setCellValue('D6', 'كشف بالعقود الخاملة لمدة '.$this->months.'  شهور .. بتاريخ '.date('Y-m-d').'  (لم تسدد بعد)');

                $event->sheet->setCellValue('B'.$this->rowcount+9, 'الإجمالي');
                $event->sheet->setCellValue('E'.$this->rowcount+9, $this->sul);
                $event->sheet->setCellValue('H'.$this->rowcount+9, $this->sul_pay);
                $event->sheet->setCellValue('I'.$this->rowcount+9, $this->raseed);
                $event->sheet
                    ->getStyle('A'.($this->rowcount+9).':J'.$this->rowcount+9)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('E8E1E1');
                $event->sheet->getDelegate()->setRightToLeft(true);

            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            8    => ['font' => ['bold' => true]],
            'A1'  => ['font' => ['size' => 20]],
            'A2'  => ['font' => ['size' => 18]],
            'D6'  => ['font' => ['bold' => true]],
            'A4'  => ['font' => ['bold' => true]],
            'B4'  => ['font' => ['bold' => true]],
            'A6'  => ['font' => ['bold' => true]],
            'B'.$this->rowcount+9  => ['font' => ['bold' => true]],
            'E'.$this->rowcount+9  => ['font' => ['bold' => true]],
            'H'.$this->rowcount+9  => ['font' => ['bold' => true]],
            'I'.$this->rowcount+9  => ['font' => ['bold' => true]],
            'E'.$this->rowcount+9 => ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1]],
            'H'.$this->rowcount+9 => ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1]],
            'I'.$this->rowcount+9 => ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1]],
        ];
    }
    public function columnFormats(): array
    {
        return [

            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,

        ];
    }
    public function columnWidths(): array
    {
        return [
            'B' => 20,
            'C' => 30,
            'D' => 14,
            'E' => 14,
            'F' => 14,
            'H' => 14,
            'I' => 14,
            'J' => 14,
          'K' => 20,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        $numericalColumns = ['B']; // columns with numerical values
        if (in_array($cell->getColumn(), $numericalColumns) && $value != '' && $value != null) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }
  /**
   * @var main_view $main_view
   */
    public function map($main_view): array
    {
      if ($main_view->raseed<=$main_view->kst) $kst_raseed=1;
      else
        $kst_raseed=ceil($main_view->raseed/$main_view->kst);
        return [
            $main_view->no,
            $main_view->acc,
            $main_view->name,
            $main_view->sul_date,
            $main_view->sul,
            $main_view->kst_count,
            $main_view->kst,
            $main_view->sul_pay,
            $main_view->raseed,
            $kst_raseed,
            $main_view->ksm_date,
        ];
    }
    public function headings(): array
    {
        $cus=Customers::where('Company',Auth::user()->company)->first();
        return [
            [$cus->CompanyName],
            [$cus->CompanyNameSuffix],
            [' '],
            ['المصرف',$this->bank_name],
            [''],
            [''],
            [''],
            ['رقم العقد','رقم الحساب','الاسم','تاريخ العقد','اجمالي التقسيط','عدد الأقساط','القسط','المسدد','المطلوب','عدد المتبقية','تاريخ أخر قسط سدد'],
        ];
    }

    public function collection()
    {

        DB::connection(Auth()->user()->company)->table('late')->delete();
        if ($this->ByTajmeehy=='Bank')
            DB::connection(Auth()->user()->company)->statement( 'insert into late select main.no,DATEDIFF(month,max(ksm_date),getdate()),:emp
                            from main,kst_trans where main.no=kst_trans.no and (SUL_PAY)<(SUL-1) and main.no<>0 and sul_pay<>0
                            and bank=:bank group by main.no having DATEDIFF(month,max(ksm_date),getdate())>=:months ',
                array('bank'=> $this->bank_no,'emp'=>Auth::user()->empno,'months'=>$this->months ));
        if ($this->ByTajmeehy=='Taj')
            DB::connection(Auth()->user()->company)->statement( 'insert into late select main.no,DATEDIFF(month,max(ksm_date),getdate()),:emp
                        from main,kst_trans where main.no=kst_trans.no and (SUL_PAY)<(SUL-1) and main.no<>0 and sul_pay<>0
                        and bank in (select bank_no from bank where bank_tajmeeh=:taj)
                        group by main.no having DATEDIFF(month,max(ksm_date),getdate())>=:months ',
                array('taj'=> $this->TajNo,'emp'=>Auth::user()->empno,'months'=>$this->months ));



        if ($this->ByTajmeehy=='Bank')
            DB::connection(Auth()->user()->company)->statement('insert into late select main.no,DATEDIFF(month,sul_date,getdate()),:emp from main
                            where  sul_pay=0 and  main.no<>0 and DATEDIFF(month,sul_date,getdate())>=:months and bank=:bank ',
                array('bank'=> $this->bank_no,'emp'=>Auth::user()->empno,'months'=>$this->months ));

        if ($this->ByTajmeehy=='Taj')
            DB::connection(Auth()->user()->company)->statement('insert into late select main.no,DATEDIFF(month,sul_date,getdate()),:emp from main
                    where  sul_pay=0 and  main.no<>0 and DATEDIFF(month,sul_date,getdate())>=:months
                    and bank in (select bank_no from bank where bank_tajmeeh=:taj) ',
                array('taj'=> $this->TajNo,'emp'=>Auth::user()->empno,'months'=>$this->months ));

      if ($this->RepRadio=='all') {
        $first = DB::connection(Auth()->user()->company)->table('main_trans_view2')

          ->selectRaw('no,name,sul_date,sul,sul_pay,raseed,kst,kst_count,bank_name,acc,order_no,max(ksm_date) as ksm_date')
            ->when($this->ByTajmeehy=='Bank',function($q){
                $q->where('bank', '=', $this->bank_no);
            })
            ->when($this->ByTajmeehy=='Taj',function($q){
                $q-> whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$this->TajNo);});
            })
            ->where('sul_pay','!=',0)

          ->whereExists(function ($query) {
            $query->select(DB::raw(1))
              ->from('late')
              ->whereColumn('main_trans_view2.no', 'late.no')
              ->where('emp', Auth::user()->empno);
          })
          ->groupBy('no', 'name', 'sul_date', 'sul', 'sul_pay', 'raseed', 'kst', 'kst_count', 'bank_name', 'acc',
            'order_no');

        $second = DB::connection(Auth()->user()->company)->table('main_view')
          ->selectraw('no,name,sul_date,sul,sul_pay,raseed,kst,kst_count,bank_name,acc,order_no,null as ksm_date')
            ->when($this->ByTajmeehy=='Bank',function($q){
                $q->where('bank', '=', $this->bank_no);
            })
            ->when($this->ByTajmeehy=='Taj',function($q){
                $q-> whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$this->TajNo);});
            })
            ->where('sul_pay','=',0)
          ->whereExists(function ($query) {
            $query->select(DB::raw(1))
              ->from('late')
              ->whereColumn('main_view.no', 'late.no')
              ->where('emp', Auth::user()->empno);
          })
          ->union($first)
          ->orderby('no')
          ->get();
      }
      if ($this->RepRadio=='some'){

        $second=DB::connection(Auth()->user()->company)->table('main_view')
          ->selectraw('no,name,sul_date,sul,sul_pay,raseed,kst,kst_count,bank_name,acc,order_no,null as ksm_date')

            ->when($this->ByTajmeehy=='Bank',function($q){
                $q->where('bank', '=', $this->bank_no);
            })
            ->when($this->ByTajmeehy=='Taj',function($q){
                $q-> whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$this->TajNo);});
            })
            ->where('sul_pay','=',0)
          ->whereExists(function ($query) {
            $query->select(DB::raw(1))
              ->from('late')
              ->whereColumn('main_view.no', 'late.no')
              ->where('emp',Auth::user()->empno);
          })

          ->get();

      }
        $this->rowcount=$second->count();
        $this->sul=$second->sum('sul');
        $this->sul_pay=$second->sum('sul_pay');
        $this->raseed=$second->sum('raseed');
        return $second;
    }

 }
