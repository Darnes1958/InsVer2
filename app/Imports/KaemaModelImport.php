<?php

namespace App\Imports;



use Illuminate\Database\Eloquent\Model;
use App\Models\excel\Kaema;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KaemaModelImport implements ToModel, WithHeadingRow
{
  /**
   * @param array $row
   *
   * @return Model|null
   */
  public function model(array $row)
  {

    if (!isset($row['قيمة القسط']) || !isset($row['اسم الزبون']) || !isset($row['حساب الجديد لزبون'])
      || !isset($row['صلاحية العقد'])) {
      return null;
    }

     else
       $rec= Kaema::create(
         [
           'name' => $row['اسم الزبون'],
           'acc' => $row['حساب الجديد لزبون'],
           'kst' => $row['قيمة القسط'],
           'sul_date' => Date::excelToDateTimeObject($row['صلاحية العقد']),
           'bankcode' => $row['فرع الزبون'],
           'no_bank' => $row['رقم العقد'],
         ]
       );

    return  $rec;
  }//
  public function headingRow(): int
  {
    return 10;
  }
}
