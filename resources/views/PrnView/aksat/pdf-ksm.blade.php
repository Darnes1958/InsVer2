@extends('PrnView.PrnMaster2')

@section('mainrep')

<div  >

  <div >
    <label style="font-size: 10pt;">{{$bank_name}}</label>
    <label style="font-size: 12pt;margin-right: 12px;" >المصرف : </label>
  </div>


  <table  width="100%"   align="right" >
    @if($RepRadio=='Geted')
     <caption style="font-size: 12pt; margin: 8px;">{{'كشف بالأقساط المخصومة من تاريخ '.$rep_date1.' إلي تاريخ '.$rep_date2 }} </caption>
    @else
      <caption style="font-size: 12pt; margin: 8px;">{{'كشف بالأقساط الغير محصلة من تاريخ '.$rep_date1.' إلي تاريخ '.$rep_date2 }} </caption>
    @endif
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      @if($RepRadio=='Geted')
      <th style="width: 8%">الخصم</th>
      <th style="width: 10%">تاريخ الخصم</th>
      @else
        <th style="width: 10%">ت.أخر قسط</th>
        <th style="width: 8%">القسط</th>
      @endif
      <th style="width: 8%">المطلوب</th>
      <th style="width: 8%">المسدد</th>
      <th style="width: 8%"> ج.التقسيط</th>
      <th style="width: 10%">تاريخ العقد</th>
      <th >الاسم</th>
      <th style="width: 14%">رقم الحساب</th>
      <th style="width: 8%">رقم العقد</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @php $sumksm=0;$sumraseed=0;$sumsul_pay=0;$sumsul=0; @endphp
    @foreach($res as $key => $item)
      <tr >
        @if($RepRadio=='Geted')
        <td> {{ $item->ksm }} </td>
        <td style="text-align: center;"> {{ $item->ksm_date }} </td>
        @else
          <td style="text-align: center;"> {{ $item->ksm_date }} </td>
          <td > {{ $item->kst }} </td>
        @endif
        <td> {{ $item->raseed }} </td>
        <td> {{ $item->sul_pay }} </td>
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
      @if($RepRadio=='Geted')
       @php $sumksm+=$item->ksm;$sumraseed+=$item->raseed;$sumsul_pay+=$item->sul_pay;$sumsul+=$item->sul; @endphp
      @else
        @php $sumraseed+=$item->raseed;$sumsul_pay+=$item->sul_pay;$sumsul+=$item->sul; @endphp
      @endif
    @endforeach
    <tr class="font-size-12 " style="font-weight: bold">
      @if($RepRadio=='Geted')
      <td> {{number_format($sumksm, 2, '.', ',')}} </td>
      @else
        <td></td>
      @endif
      <td>   </td>
      <td> {{number_format($sumraseed, 2, '.', ',')}}  </td>
      <td> {{number_format($sumsul_pay, 2, '.', ',')}}  </td>

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

