<?php

namespace App\Exports;

use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use App\Models\bank\bank;
use App\Models\bank\BankTajmeehy;
use App\Models\bank\rep_bank;
use App\Models\Customers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Khasf  implements FromCollection,WithMapping, WithHeadings,
    WithEvents,WithColumnWidths,WithStyles,WithColumnFormatting
{
    public $bank;
    public $ByTajmeehy;
    public $TajNo;
    public $from;
    public $rowcount;
    public $sul;
    public $sul_pay;
    public $raseed;
    public $kst;
    public $name;
    public $title;
    /**
     * @return array
     */
    public function __construct(string $ByTjmeehy,int $TajNo, int $bank,string $from)
{
    $this->bank = $bank;
    $this->ByTajmeehy=$ByTjmeehy;
    $this->TajNo=$TajNo;
    $this->from=$from;
    if ($from=='main') $this->title='كشف بالعقود القائمة حتي تاريخ '.date('Y-m-d');
    else $this->title='كشف بالعقود (الأرشيف) حتي تاريخ '.date('Y-m-d');
    if ($this->ByTajmeehy=='Bank')
        $this->name=bank::find($this->bank)->bank_name;
    else $this->name=BankTajmeehy::where('TajNo',$this->TajNo)->first()->TajName;

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
            $event->sheet->getDelegate()->getStyle('G')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('F')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $event->sheet->setCellValue('D6',$this->title );
            $event->sheet->setCellValue('B'.$this->rowcount+9, 'الإجمالي');
            $event->sheet->setCellValue('E'.$this->rowcount+9, $this->sul);
            $event->sheet->setCellValue('G'.$this->rowcount+9, $this->kst);
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
        'G'.$this->rowcount+9  => ['font' => ['bold' => true]],
        'H'.$this->rowcount+9  => ['font' => ['bold' => true]],
        'I'.$this->rowcount+9  => ['font' => ['bold' => true]],

        'E'.$this->rowcount+9 => ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1]],
        'G'.$this->rowcount+9 => ['numberFormat' => ['formatCode' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1]],
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
        'G' => 14,
        'H' => 14,
        'I' => 14,


    ];
}
    /**
     * @var main $rep_bank
     */
    public function map($rep_bank): array
{
    return [
        $rep_bank->no,
        $rep_bank->acc,
        $rep_bank->name,
        $rep_bank->sul_date,
        $rep_bank->sul,
        $rep_bank->kst_count,

        $rep_bank->kst,
        $rep_bank->sul_pay,
        $rep_bank->raseed,
    ];
}
    public function headings(): array
    {
        $cus=Customers::where('Company',Auth::user()->company)->first();
        return [
            [$cus->CompanyName],
            [$cus->CompanyNameSuffix],
            [' '],
            ['المصرف',$this->name],
            [''],
            [''],
            [''],
            ['رقم العقد','رقم الحساب','الاسم','تاريخ العقد','اجمالي التقسيط','عدد الأقساط','القسط','المسدد','المطلوب'],
        ];
    }

    public function collection()
    {
        if ($this->from=='main') {
            $res = main::
            when($this->ByTajmeehy == 'Bank', function ($q) {
                $q->where('bank', '=', $this->bank);
            })
                ->when($this->ByTajmeehy == 'Taj', function ($q) {
                    $q->whereIn('bank', function ($q) {
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh', $this->TajNo);
                    });
                })
                ->selectRaw('count(*) as count,sum(sul) as sul,sum(sul_pay) as sul_pay,sum(raseed) raseed')->first();
            $this->rowcount=$res->count;
            $this->sul=$res->sul;
            $this->sul_pay=$res->sul_pay;
            $this->raseed=$res->raseed;
            return main::
            when($this->ByTajmeehy == 'Bank', function ($q) {
                $q->where('bank', '=', $this->bank);
            })
                ->when($this->ByTajmeehy == 'Taj', function ($q) {
                    $q->whereIn('bank', function ($q) {
                        $q->select('bank_no')->from('bank')->where('bank_tajmeeh', $this->TajNo);
                    });
                })
                ->get();
        } else
             {
                $res = MainArc::
                when($this->ByTajmeehy == 'Bank', function ($q) {
                    $q->where('bank', '=', $this->bank);
                })
                    ->when($this->ByTajmeehy == 'Taj', function ($q) {
                        $q->whereIn('bank', function ($q) {
                            $q->select('bank_no')->from('bank')->where('bank_tajmeeh', $this->TajNo);
                        });
                    })
                    ->selectRaw('count(*) as count,sum(sul) as sul,sum(sul_pay) as sul_pay,sum(raseed) raseed')->first();
                 $this->rowcount=$res->count;
                 $this->sul=$res->sul;
                 $this->sul_pay=$res->sul_pay;
                 $this->raseed=$res->raseed;
                return MainArc::
                when($this->ByTajmeehy == 'Bank', function ($q) {
                    $q->where('bank', '=', $this->bank);
                })
                    ->when($this->ByTajmeehy == 'Taj', function ($q) {
                        $q->whereIn('bank', function ($q) {
                            $q->select('bank_no')->from('bank')->where('bank_tajmeeh', $this->TajNo);
                        });
                    })
                    ->get();
            }
        }

}
