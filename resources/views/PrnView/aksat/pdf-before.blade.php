@extends('PrnView.PrnMaster2')

@section('mainrep')

  <div  >

    @if($ByTajmeehy=='bank')
    <div >
      <label style="font-size: 10pt;">{{$bank_name}}</label>
      <label style="font-size: 12pt;margin-right: 12px;" >المصرف : </label>
    </div>
    @else
      <div >
        <label style="font-size: 10pt;">{{$TajName}}</label>
        <label style="font-size: 12pt;margin-right: 12px;" >المصرف التجميعي: </label>
      </div>
    @endif

    <table  width="100%"   align="right" >
      @if($Not_pay)
       <caption style="font-size: 12pt; margin: 8px;">{{$month.'كشف بالعقود التي لم تسدد بعد حتي شهر ' }} </caption>
      @else
        <caption style="font-size: 12pt; margin: 8px;">{{$month.'كشف بالأقساط المستحقة والمتأخرة عن شهر ' }} </caption>
      @endif
      <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
      <tr  style="background: #9dc1d3;" >
        <th style="width: 8%">مجموعها</th>
        <th style="width: 8%">عدد المتأخرة</th>
        <th style="width: 8%">عدد المسددة</th>
        <th style="width: 7%">المطلوب</th>
        <th style="width: 7%">المسدد</th>
        <th style="width: 7%">القسط</th>
        <th style="width: 7%"> ج.التقسيط</th>
        <th style="width: 7%">ت.العقد</th>
        <th >الاسم</th>
        <th style="width: 12%">رقم الحساب</th>
        <th style="width: 8%">رقم العقد</th>
      </tr>
      </thead>
      <tbody style="margin-bottom: 40px; ">
      @php $sumkst_late=0;$sumraseed=0;$sumsul_pay=0;$sumsul=0; @endphp
      @foreach($res as $key => $item)
        <tr >
          <td> {{ $item->kst_late }} </td>
          <td> {{ $item->late }} </td>

          <td> {{number_format($item->pay_count, 0, '.', ',')}}  </td>
          <td> {{ $item->raseed }} </td>
          <td> {{ $item->sul_pay }} </td>
          <td> {{ $item->kst }} </td>
          <td> {{ $item->sul }} </td>
          <td style="text-align: center;"> {{ $item->sul_date }} </td>
          <td> {{ $item->name }} </td>
          <td> {{ $item->acc }} </td>
          <td style="text-align: center;"> {{ $item->no }} </td>
        </tr>
        <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
        </div>
        @php $sumkst_late+=$item->kst_late;$sumraseed+=$item->raseed;$sumsul_pay+=$item->sul_pay;$sumsul+=$item->sul; @endphp
      @endforeach
      <tr class="font-size-12 " style="font-weight: bold">
        <td> {{number_format($sumkst_late, 2, '.', ',')}} </td>
        <td>   </td>
        <td>   </td>
        <td> {{number_format($sumraseed, 2, '.', ',')}}  </td>
        <td> {{number_format($sumsul_pay, 2, '.', ',')}}  </td>
        <td>   </td>
        <td> {{number_format($sumsul, 2, '.', ',')}}  </td>
        <td>   </td>
        <td>   </td>
        <td>   </td>
        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      </tr>
      </tbody>
    </table>
  </div>
  </div>

@endsection


