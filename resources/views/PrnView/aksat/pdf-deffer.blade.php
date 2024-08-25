

@extends('PrnView.PrnMaster')

@section('mainrep')
<div  >


    <div >
        <label style="font-size: 10pt;">{{$bank_name}}</label>
        @if($By=='Bank')
            <label style="font-size: 14pt;margin-right: 12px;" >مصرف : </label>
        @else
            <label style="font-size: 14pt;margin-right: 12px;" >للمصرف التجميعي : </label>
        @endif

    </div>

<br>

  <table  width="100%"   align="right" >
      <caption style="font-size: 14pt; margin: 8px;">{{'كشف بالأقساط المخصومة والغير مطابقة لقيمة القسط فالعقد حتي تاريخ  '.$RepDate }} </caption>
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
        <th style="width: 12%">تاريخ الخصم</th>
        <th style="width: 10%">الخصم</th>
        <th style="width: 10%">القسط</th>
        <th >الاسم</th>
        <th style="width: 16%">رقم الحساب</th>
        <th style="width: 10%">رقم العقد</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @foreach($pdfdetail as $key => $item)
      <tr >
          <td style="text-align: center;"> {{ $item->ksm_date }} </td>
          <td> {{ $item->ksm }} </td>
          <td > {{ $item->kst }} </td>
          <td> {{ $item->name }} </td>
          <td> {{ $item->acc }} </td>
          <td style="text-align: center;"> {{ $item->no }} </td>
      </tr>
      <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
      </div>
    @endforeach
    </tbody>

  </table>
</div>
@endsection