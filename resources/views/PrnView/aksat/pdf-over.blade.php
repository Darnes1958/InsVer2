@extends('PrnView.PrnMaster3')

@section('mainrep')



  <div >
      <label style="font-size: 14pt;" >المصرف : </label>
      <label style="font-size: 10pt;">{{$arr['bank_name']}}</label>
  </div>


  <table  width="90%" >
    @if($arr['Table']=='over_kst')
      @if($arr['letters']==0)
       <caption style="font-size: 12pt; margin: 8px;">{{'الأقساط المخصومة بالفائض والغير مرحلة '.$arr['date'] }} </caption>
      @else
        <caption style="font-size: 12pt; margin: 8px;">{{'الأقساط المخصومة بالفائض والمرحلة '.$arr['date'] }} </caption>
      @endif
    @else
      @if($arr['letters']==0)
        <caption style="font-size: 12pt; margin: 8px;">{{'الأقساط المخصومة بالفائض من الارشيف والغير مرحلة '.$arr['date'] }} </caption>
      @else
        <caption style="font-size: 12pt; margin: 8px;">{{'الأقساط المخصومة بالفائض من الارشيف والمرحلة '.$arr['date'] }} </caption>
      @endif
    @endif
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
        <th style="width: 10%"> الرقم الألي</th>
        <th style="width: 10%">رقم العقد</th>
        <th style="width: 20%">رقم الحساب</th>
        <th >الاسم</th>
        <th style="width: 12%">التاريخ</th>
        <th style="width: 12%">المبلغ</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @php $sumval=0 @endphp
    @foreach($res as $key => $item)
      <tr >
          <td> {{ $item->wrec_no }} </td>
          <td> {{ $item->no }} </td>
          <td style="text-align: center"> {{ $item->acc }} </td>
          <td> {{ $item->name }} </td>
          <td style="text-align: center;"> {{ $item->tar_date }} </td>
          <td> {{ $item->kst }} </td>

      </tr>

      @php $sumval+=$item->kst; @endphp
    @endforeach
    <tr class="font-size-12 " style="font-weight: bold">

        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      <td>   </td>
      <td>   </td>
      <td>   </td>
        <td>   </td>
        <td> {{number_format($sumval, 2, '.', ',')}} </td>
    </tr>

    </tbody>

  </table>
</div>
</div>


@endsection
