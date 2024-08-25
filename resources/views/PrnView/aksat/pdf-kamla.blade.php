@extends('PrnView.PrnMaster2')

@section('mainrep')

<div  >

  <div >
    <label style="font-size: 10pt;">{{$bank_name}}</label>
    <label style="font-size: 12pt;margin-right: 12px;" >المصرف : </label>
  </div>


  <table  width="100%"   align="right" >
   @if($RepRadio=='RepAll') <caption style="font-size: 12pt; margin: 8px;">{{'كشف بالعقود الخاملة لمدة '.$months.'  شهور .. بتاريخ '.$RepDate }} </caption>@endif
   @if($RepRadio=='RepSome') <caption style="font-size: 12pt; margin: 8px;">{{'كشف بالعقود الخاملة لمدة '.$months.'  شهور .. بتاريخ '.$RepDate.'  (لم تسدد بعد)' }} </caption>@endif
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      <th style="width: 8%">ت.أخر قسط</th>
      <th style="width: 8%">ع.المتبقية</th>
      <th style="width: 8%">المطلوب</th>
      <th style="width: 8%">المسدد</th>
      <th style="width: 8%">القسط</th>
      <th style="width: 8%"> ج.التقسيط</th>
      <th style="width: 8%">ت.العقد</th>
      <th >الاسم</th>
      <th style="width: 12%">رقم الحساب</th>
      <th style="width: 8%">رقم العقد</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @php $sumkst=0;$sumraseed=0;$sumsul_pay=0;$sumsul=0; @endphp
    @foreach($res as $key => $item)
      @php
        {{ if ($item->raseed<=$item->kst) $kst_raseed=1;
        else
          $kst_raseed=ceil($item->raseed/$item->kst);
          }}
      @endphp
      <tr >
        <td style="text-align: center;"> {{ $item->ksm_date }} </td>
        <td style="text-align: center;"> {{ $kst_raseed }} </td>
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
      @php $sumkst+=$item->kst;$sumraseed+=$item->raseed;$sumsul_pay+=$item->sul_pay;$sumsul+=$item->sul; @endphp
    @endforeach
    <tr class="font-size-12 " style="font-weight: bold">
      <td>   </td>
      <td>   </td>
      <td> {{number_format($sumraseed, 2, '.', ',')}}  </td>
      <td> {{number_format($sumsul_pay, 2, '.', ',')}}  </td>
      <td> {{number_format($sumkst, 2, '.', ',')}} </td>
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

