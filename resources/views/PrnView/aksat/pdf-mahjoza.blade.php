@extends('PrnView.PrnMaster')

@section('mainrep')



  <div >
    <label style="font-size: 10pt;">{{$bank_name}}</label>
    <label style="font-size: 14pt;margin-right: 12px;" >المصرف : </label>
  </div>


  <table  width="100%"   align="right" >

       <caption style="font-size: 12pt; margin: 8px;">{{'الأقساط الغير محجوزة حتي تاريخ '.$date }} </caption>

    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      <th style="width: 14%">القسط</th>
      <th style="width: 14%">التاريخ</th>
      <th >الاسم</th>
      <th style="width: 20%">رقم الحساب</th>
      <th style="width: 14%"> الرقم الألي</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">

    @foreach($res as $key => $item)
      <tr >
        <td> {{ $item->kst }} </td>
        <td style="text-align: center;"> {{ $item->sul_date }} </td>
        <td> {{ $item->name }} </td>
        <td style="text-align: center;"> {{ $item->acc }} </td>
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



@endsection
