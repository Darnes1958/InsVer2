<?php

namespace App\Exports;

use App\Models\aksat\main_view;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Motakra extends DefaultValueBinder implements FromCollection,WithMapping, WithHeadings,
    WithEvents,WithColumnWidths,WithStyles,WithColumnFormatting,WithCustomValueBinder
{
    public $bank_name;
    public $ByTajmeehy;
    public $TajNo;
    public $bank_no;
    public $months;
    public $Not_pay;
    public $rowcount;
    public $sul;
    public $sul_pay;
    public $raseed;


    /**
     * @return array
     */
    public function __construct(string $ByTajmeehy,int $TajNo,int $bank,string $Not_pay,string $bank_name)
    {
        $this->ByTajmeehy=$ByTajmeehy;
        $this->TajNo=$TajNo;
        $this->bank_no = $bank;

        $this->Not_pay = $Not_pay;
        $this->bank_name = $bank_name;
    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event)  {
                $event->sheet
                    ->getStyle('A8:L8')
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

                if ($this->Not_pay=='all')
                    $event->sheet->setCellValue('D6', 'كشف بالأقساط المستحقة والمتأخرة عن شهر '.$this->months.'   بتاريخ '.date('Y-m-d'));
                if ($this->Not_pay=='some')
                    $event->sheet->setCellValue('D6', 'كشف بالعقود التي لم تسدد بعد حتي شهر '.$this->months.'   بتاريخ '.date('Y-m-d').'  (لم تسدد بعد)');

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
     * @var main_view $main
     */
    public function map($main): array
    {

        return [
            $main->no,
            $main->acc,
            $main->name,
            $main->sul_date,
            $main->sul,
            $main->kst_count,
            $main->kst,
            $main->sul_pay,
            $main->raseed,
            $main->pay_count,
            $main->late,
            $main->kst_late,

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
            ['رقم العقد','رقم الحساب','الاسم','تاريخ العقد','اجمالي التقسيط','عدد الأقساط','القسط','المسدد','المطلوب','عدد المسدد','عدد المتأخرة','مجموعها'],
        ];
    }

    public function collection()
    {

        $res=DB::connection(Auth()->user()->company)->table('settings')
            ->where('no',3)->first();
        $DAY_OF_KSM=$res->s1;
        $day=Carbon::now()->day;
        $month=Carbon::now()->month;
        $year=Carbon::now()->year;

        $this->months=$month.'\\'.$year;

        $RepDate=date('Y-m-d');
        $cus=Customers::where('Company',Auth::user()->company)->first();
        $RepTable= DB::connection(Auth()->user()->company)->table('main')
            ->join('late','main.no','=','late.no')
            ->selectRaw('acc,name,sul_date,sul,kst_count,sul_pay,raseed,main.kst,main.no,round((sul_pay/kst),0) pay_count,late,
                               late*main.kst kst_late')
            ->when($this->ByTajmeehy=='Bank',function ($q){
                return $q->where('bank',$this->bank_no);
            })
            ->when($this->ByTajmeehy=='Taj',function ($q){
                return $q->whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$this->TajNo);});
            })

            ->when($this->Not_pay=='some',function($q){
                return $q->where([

                    ['sul_pay',0],
                    ['late', '>', 0],
                    ['kst','!=',0],]);})
            ->when( $this->Not_pay=='all',function ($q) {
                return $q->where([

                    ['late', '>', 0],
                    ['kst','!=',0],]);})
            ->get();

        $this->rowcount=$RepTable->count();
        $this->sul=$RepTable->sum('sul');
        $this->sul_pay=$RepTable->sum('sul_pay');
        $this->raseed=$RepTable->sum('raseed');
        return $RepTable;
    }

}

