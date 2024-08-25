@extends('PrnView.PrnMaster')

@section('mainrep')
  <div  >
      <div style="text-align: center;font-size: 16pt">مخزون {{$place_name}}</div>
      <br>
    @foreach($item_type as $key=> $item)
    @php
      $type_name=$item->type_name;
      $filter = $res->filter(function ($items) use ($type_name) {
      return $items->type_name==$type_name;
      });
    @endphp

  @if(count($filter)>1)

    <table style="border-collapse:collapse;"  >

      <caption>{{$type_name}}</caption>
      <thead>
      <tr style="background:lightgray">

        <th style="width: 14%">الرصيد الكلي</th>
        <th style="width: 14%">رصيد المكان</th>
        <th style="width: 14%">سعر البيع</th>
        <th >اسم الصنف</th>
        <th style="width: 14%">رقم الصنف</th>
      </tr>
      </thead>
      <tbody style="margin-bottom: 40px; ">
      @foreach($filter as $key => $item)
        <tr class="font-size-12">
          <td style="text-align: center">  {{ $item->raseed }}</td>
          <td style="text-align: center">  {{ $item->place_ras }}</td>
          <td> {{ $item->price_sell }} </td>
          <td> {{ $item->item_name }} </td>
          <td> {{ $item->item_no }} </td>
        </tr>

      @endforeach
      </tbody>
    </table>
   @endif
  @endforeach
  </div>
@endsection

