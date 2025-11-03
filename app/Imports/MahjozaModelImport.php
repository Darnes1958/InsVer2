<?php

namespace App\Imports;


use Illuminate\Database\Eloquent\Model;
use App\Models\excel\Mahjoza;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MahjozaModelImport implements ToModel, WithHeadingRow
{
/**
   * @param array $row
   *
   * @return Model|null
   */
  public function model(array $row)
{

    if (!isset($row['aadd_alaksat']) || !isset($row['alasm']) || !isset($row['hsab_alzbon'])
        || !isset($row['tarykh_akhr_mrtb'])) {
        return null;
    }

    $rec= Mahjoza::on(auth()->user()->company)->create(
        [
            'name' => $row['alasm'],
            'acc' => $row['hsab_alzbon'],
            'aksat_tot' => $row['agmaly_alaksat'],
            'aksat_count' => $row['aadd_alaksat'],
            'sal_date' => Date::excelToDateTimeObject($row['tarykh_akhr_mrtb']),
        ]
    );

    return  $rec;
}//
  public function headingRow(): int
{
    return 12;
}
}
