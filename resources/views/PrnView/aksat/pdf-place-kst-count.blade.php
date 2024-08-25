@extends('PrnView.PrnMaster')

@section('mainrep')



  <div >
    <label style="font-size: 10pt;">{{$place_name}}</label>
    <label style="font-size: 14pt;margin-right: 12px;" >نقطة البيع : </label>
  </div>


  <table  width="100%"   align="right" >
    <caption style="font-size: 12pt; margin: 8px;">{{'كشف باعداد الأقساط المحصلة والمتبقية حتي تاريخ '.$RepDate }} </caption>
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      <th style="width: 12%">ع.المتبقية</th>
      <th style="width: 12%">ع.المخصومة</th>
      <th style="width: 12%">المتبقي</th>
      <th style="width: 12%">المسدد</th>
      <th style="width: 12%">الإجمالي</th>
      <th style="width: 12%">ع.العقود</th>
      <th >المصرف</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">

    @foreach($res as $key => $item)
      <tr >
        <td  style="text-align: center;"> {{ $item->kst_count_not }} </td>
        <td style="text-align: center;"> {{ $item->kst_count }} </td>
        <td> {{ number_format($item->sumraseed, 2, '.', ',') }} </td>
        <td > {{number_format( $item->sumpay, 2, '.', ',') }} </td>
        <td> {{ number_format($item->sumsul, 2, '.', ',') }} </td>
        <td  style="text-align: center;"> {{ $item->WCOUNT }} </td>
        <td> {{ $item->bank_name }} </td>
      </tr>
      <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
        <label class="page"></label>
        <label> صفحة رقم </label>
      </div>

    @endforeach
    <tr class="font-size-12 " style="font-weight: bold">
      <td style="text-align: center;"> {{number_format($PlaceTableSum->kst_count_not, 0, '', '')}}  </td>
      <td style="text-align: center;"> {{number_format($PlaceTableSum->kst_count, 0, '', '')}}  </td>
      <td>  {{number_format($PlaceTableSum->sumraseed, 2, '.', ',')}} </td>
      <td>  {{number_format($PlaceTableSum->sumpay, 2, '.', ',')}} </td>
      <td>  {{number_format($PlaceTableSum->sumsul, 2, '.', ',')}} </td>
      <td  style="text-align: center;">  {{number_format($PlaceTableSum->WCOUNT, 0, '', '')}} </td>
      <td style="font-weight:normal;">الإجمــــــــالي  </td>
    </tr>


    </tbody>

  </table>
</div>
</div>


@endsection
