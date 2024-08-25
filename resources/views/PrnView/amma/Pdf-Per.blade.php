@extends('PrnView.PrnMaster')

@section('mainrep')

    <div style="position: fixed; text-align: center;  width: 100%;  margin: 10px;
                              display: flex;  justify-content: center;">
      <label style="font-size: 14pt;">{{$per_no}}</label>
      <label  style="width: 20%;font-size: 14pt;">إذن صرف مخازن رقم </label>
    </div>
    <br>
    <br>
    <table style=" border: none;margin-bottom: 5px; padding-right: 5%">
        <tbody >
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 50%; ">  </td>
                <td class="order-td" style="width: 15%; text-align: center"> {{$res->exp_date}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> بتاريخ </td>
            </tr>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 35%; ">  </td>
                <td class="order-td" style="width: 35%; "> {{$res->st_name}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> مــــــن </td>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 35%; ">  </td>
                <td class="order-td" style="width: 35%;"> {{$res->place_name}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> إلـــــي </td>
            </tr>
        </tbody>
    </table>
<br>

    <table style="width:  80%; margin-left: 10%;margin-right: 10%;">

        <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
        <tr  style="background: #9dc1d3;" >

            <th style="width: 16%">الكمية</th>
            <th >إسم الصنف</th>
            <th style="width: 20%">رقم الصنف</th>
            <th style="width: 12%">ت</th>

        </tr>
        </thead>
        <tbody style="margin-bottom: 40px; ">
        @foreach($res2 as $key => $item)
            <tr>
                <td> {{ $item->quant }} </td>
                <td > {{ $item->item_name }} </td>
                <td style="text-align: center"> {{ $item->item_no }} </td>
                <td style="text-align: center"> {{ $key }} </td>
            </tr>
        @endforeach
        </tbody>

    </table>

@endsection







