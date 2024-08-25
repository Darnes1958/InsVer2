@extends('PrnView.PrnMaster')

@section('mainrep')
<div>

  <div style="text-align: center">
    <label style="font-size: 10pt;">{{$date2}}</label>
    <label style="font-size: 14pt;margin-right: 12px;" > إلي تاريخ : </label>
    <label style="font-size: 10pt;">{{$date1}}</label>
    <label style="font-size: 14pt;margin-right: 12px;" >تقرير بإجمالي العقود حسب المصارف من تاريخ : </label>


  </div>

  <table style="width:  90%; margin-left: 5%;margin-right: 5%; margin-bottom: 4%; margin-top: 2%;">
    <thead style="  margin-top: 8px;">
    <tr style="background: #9dc1d3;">
      <th style="width: 14%">المتبقي</th>
      <th style="width: 14%">المسدد</th>
      <th style="width: 14%">اجمالي العقود</th>
      <th style="width: 14%">عدد العقود</th>
      <th>اسم المصرف</th>
      <th style="width: 12%">رقم المصرف</th>
    </tr>
    </thead>
    <tbody id="addRow" class="addRow">
    @foreach($RepTable as $key=> $item)
    <tr >
      <td> {{ number_format($item->sumraseed,2, '.', ',') }} </td>
      <td> {{ number_format($item->sumpay,2, '.', ',') }} </td>
      <td> {{ number_format($item->sumsul,2, '.', ',') }} </td>
      <td> {{ $item->WCOUNT }} </td>
      <td> {{ $item->bank_name }} </td>
      <td> {{ $item->bank}} </td>
    </tr>
    @endforeach
    <tr style="background: #9dc1d3;">
      <td style="font-weight: bold"> {{ number_format($raseed,2, '.', ',') }} </td>
      <td style="font-weight: bold"> {{ number_format($pay,2, '.', ',') }} </td>
      <td style="font-weight: bold"> {{ number_format($sul,2, '.', ',') }} </td>
      <td style="font-weight: bold"> {{ number_format($count,0, '.', ',') }} </td>
      <td colspan="2" style="text-align: center;"> الإجمــــــالي </td>

    </tr>
    </tbody>
  </table>


</div>



@endsection

