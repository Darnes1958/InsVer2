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

    if (!isset($row['kym_alkst']) || !isset($row['asm_alzbon']) || !isset($row['hsab_algdyd_lzbon'])
      || !isset($row['slahy_alaakd'])) {
      return null;
    }

     else
       $rec= Kaema::create(
         [
           'name' => $row['asm_alzbon'],
           'acc' => $row['hsab_algdyd_lzbon'],
           'kst' => $row['kym_alkst'],
           'sul_date' => Date::excelToDateTimeObject($row['slahy_alaakd']),
           'bankcode' => $row['fraa_alzbon'],
           'no_bank' => $row['rkm_alaakd'],
         ]
       );

    return  $rec;
  }//
  public function headingRow(): int
  {
    return 10;
  }
}
