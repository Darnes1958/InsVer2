@extends('PrnView.PrnMaster')

@section('mainrep')

<div  >

  <div >
    <label style="font-size: 10pt;">{{$TajName}}</label>
    <label style="font-size: 12pt;margin-right: 12px;" >المصرف التجميعي : </label>
  </div>
  @if($bank_no!=0)
    <div >
      <label style="font-size: 10pt;">{{$bank_name}}</label>
      <label style="font-size: 12pt;margin-right: 12px;" >الفرع : </label>
    </div>
  @endif

  <table     >
    <caption style="font-size: 12pt; margin: 8px;">{{' كشف بالعقود المدخلة لدينا والغير قائمة بالمصرف  حتي '.$RepDate }} </caption>
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      <th style="width: 10%"> القسط</th>
      <th style="width: 12%"> المطلوب</th>
      <th style="width: 12%"> المسدد</th>
      <th style="width: 14%">اجمالي العقود</th>

      <th >الإسم</th>
      <th style="width: 16%">رقم الحساب</th>
      <th style="width: 10%">رقم العقد</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @php $sumkst=0;$sumsul=0;$sumsul_pay=0;$sumraseed=0; @endphp
    @foreach($res as $key => $item)
      <tr >
        <td> {{number_format($item->kst, 2, '.', '')}} </td>
        <td> {{ $item->raseed }} </td>
        <td> {{ $item->sul_pay }} </td>
        <td> {{ $item->sul }} </td>
        <td> {{ $item->name }} </td>
        <td> {{ $item->acc }} </td>
        <td> {{ $item->no }} </td>
      </tr>
      <div id="footer" style="height: 50px; width: 96%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
        <label class="page"></label>
        <label> صفحة رقم </label>
      </div>
      @php $sumkst+=$item->kst;$sumsul+=$item->sul;$sumsul_pay+=$item->sul_pay;$sumraseed+=$item->raseed; @endphp
    @endforeach
    <tr class="font-size-12 " style="font-weight: bold">



      <td> {{number_format($sumraseed, 2, '.', ',')}}  </td>
      <td> {{number_format($sumsul_pay, 2, '.', ',')}}  </td>
      <td> {{number_format($sumsul, 2, '.', ',')}}  </td>
      <td> {{number_format($sumkst, 2, '.', ',')}}  </td>
      <td>   </td>
      <td>   </td>
      <td style="font-weight:normal;">الإجمــــــــالي  </td>
    </tr>
    </tbody>
  </table>
</div>
</div>

@endsection

