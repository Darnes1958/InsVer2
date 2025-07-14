<?php

namespace App\Imports;

use App\Models\ExcelSeting;
use App\Models\FromExcel;
use Illuminate\Support\Facades\Auth;
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
       // info($row);
        $bank=ExcelSeting::find(Auth::user()->empno);
      if (!isset($row[$bank->name])  || !isset($row[$bank->acc])
        || !isset($row[$bank->ksm_date])) {
        return null;
      }



      $rec= FromExcel::on(auth()->user()->company)->create(
        [
          'name' => $row[$bank->name],
          'acc' => $row[$bank->acc],
          'ksm' => $row[$bank->ksm],
          'ksm_date' => Date::excelToDateTimeObject($row[$bank->ksm_date]),
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
