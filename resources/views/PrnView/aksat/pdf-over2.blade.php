@extends('PrnView.PrnMaster3')

@section('mainrep')






  <table  width="100%"   align="right" >

    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      <th style="width: 14%">المبلغ</th>
      <th style="width: 14%">التاريخ</th>
      <th >الاسم</th>
      <th style="width: 20%">رقم الحساب</th>
      <th style="width: 10%"> الرقم الألي</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @php $sumval=0 @endphp
    @foreach($res as $key => $item)
      <tr >
        <td> {{ $item->kst }} </td>
        <td style="text-align: center;"> {{ $item->tar_date }} </td>
        <td> {{ $item->name }} </td>
        <td > {{ $item->acc }} </td>
        <td> {{ $item->wrec_no }} </td>
      </tr>

      @php $sumval+=$item->kst; @endphp
    @endforeach
    <tr class="font-size-12 " style="font-weight: bold">
      <td> {{number_format($sumval, 2, '.', ',')}} </td>
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
