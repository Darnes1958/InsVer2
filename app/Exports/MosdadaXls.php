<?php
namespace App\Exports;

use App\Invoice;
use App\Models\aksat\main_view;
use App\Models\bank\bank;
use App\Models\Customers;
use Illuminate\Contracts\View\View;
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




class MosdadaXls implements FromCollection,WithMapping, WithHeadings,
                            WithEvents,WithColumnWidths,WithStyles,WithColumnFormatting
 {
    public $bank_name;
    public $ByTajmeehy;
    public $TajNo;
    public $bank;
    public $baky;
    public $rowcount;
    public $sul;
    public $sul_pay;
    public $raseed;


    /**
     * @return array
     */
    public function __construct(string $ByTajmeehy,int $TajNo,int $bank,int $baky,string $bank_name)
    {
        $this->ByTajmeehy=$ByTajmeehy;
        $this->TajNo=$TajNo;
        $this->bank = $bank;
        $this->baky = $baky;
        $this->bank_name = $bank_name;
    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event)  {
                $event->sheet
                    ->getStyle('A8:I8')
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
                $event->sheet->setCellValue('D6', 'كشف بالعقود المسددة بتاريخ '.date('Y-m-d'));
                $event->sheet->setCellValue('B'.$this->rowcount+9, 'الإجمالي');
                $event->sheet->setCellValue('E'.$this->rowcount+9, $this->sul);
                $event->sheet->setCellValue('H'.$this->rowcount+9, $this->sul_pay);
                $event->sheet->setCellValue('I'.$this->rowcount+9, $this->raseed);
                $event->sheet
                    ->getStyle('A'.($this->rowcount+9).':I'.$this->rowcount+9)
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
        ];
    }
    /**
     * @var main_view $main_view
     */
    public function map($main_view): array
    {
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
            ['رقم العقد','رقم الحساب','الاسم','تاريخ العقد','اجمالي التقسيط','عدد الأقساط','القسط','المسدد','المطلوب'],
        ];
    }

    public function collection()
    {
        $res=main_view::
            when($this->ByTajmeehy=='Bank',function($q){
                $q->where('bank', '=', $this->bank);
            })
            ->when($this->ByTajmeehy=='Taj',function($q){
                $q-> whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$this->TajNo);});
            })

            ->where('raseed','<=',$this->baky)
            ->selectRaw('count(*) as count,sum(sul) as sul,sum(sul_pay) as sul_pay,sum(raseed) raseed')->first();
        $this->rowcount=$res->count;
        $this->sul=$res->sul;
        $this->sul_pay=$res->sul_pay;
        $this->raseed=$res->raseed;
        return main_view::
             when($this->ByTajmeehy=='Bank',function($q){
                    $q->where('bank', '=', $this->bank);
                })
            ->when($this->ByTajmeehy=='Taj',function($q){
                $q-> whereIn('bank', function($q){
                    $q->select('bank_no')->from('bank')->where('bank_tajmeeh',$this->TajNo);});
            })
            ->where('raseed','<=',$this->baky)
            ->get();
    }

 }
