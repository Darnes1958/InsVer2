@extends('PrnView.PrnMaster')

@section('mainrep')
  <div  >
    @foreach($item_type as $key=> $item)
    @php
      $type_name=$item->type_name;
      $filter = $res->filter(function ($items) use ($type_name) {
      return $items->type_name==$type_name;
      });
    @endphp
    <table style="border-collapse:collapse;"  >
      <caption>{{$res[0]['place_name']}}</caption>
      <caption>{{$type_name}}</caption>
      <thead>
      <tr style="background:lightgray">
        <th style="width: 30%">ملاحظات</th>
        <th style="width: 12%">الرصيد</th>
        <th >اسم الصنف</th>
        <th style="width: 12%">رقم الصنف</th>
      </tr>
      </thead>
      <tbody style="margin-bottom: 40px; ">
      @foreach($filter as $key => $item)
        <tr class="font-size-12">
          <td>  </td>
          <td>  </td>
          <td> {{ $item->item_name }} </td>
          <td> {{ $item->item_no }} </td>
        </tr>

      @endforeach
      </tbody>
    </table>
      <div class="page-break"></div>
    @endforeach
  </div>
@endsection

