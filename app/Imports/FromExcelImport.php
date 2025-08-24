<?php

namespace App\Imports;

use App\Models\ExcelSeting;
use App\Models\FromExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FromExcelImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $bank=ExcelSeting::find(Auth::user()->empno);
      if (
          $row[$bank->name]==null  || $row[$bank->acc]==null
        || $row[$bank->ksm_date]==null || $row[$bank->ksm]==null
      )
      {
        return null;
      }
        if (
            !is_numeric($row[$bank->ksm])  || !is_numeric($row[$bank->acc])

        )
        {
            return null;
        }
        try {
            $date = Carbon::createFromFormat('d/m/Y', $row[$bank->ksm_date]);
            } catch(InvalidArgumentException $x) {
          $date=  Date::excelToDateTimeObject($row[$bank->ksm_date]);
        }

      $ksm=$row[$bank->ksm];
      if (Auth::user()->company=='Boshlak')  $ksm -=(.05*$ksm);


      $rec= FromExcel::on(auth()->user()->company)->create(
        [
          'name' => $row[$bank->name],
          'acc' => $row[$bank->acc],
          'ksm' => $ksm,
          'ksm_date' =>$date,
          'bank' => 0,
          'hafitha_tajmeehy' => Auth::user()->IsAdmin,
          'h_no' => 1,
        ]
      );

      return  $rec;
    }//
    public function headingRow(): int
        {
          return ExcelSeting::find(Auth::user()->empno)->headRowNo ;

        }

}
