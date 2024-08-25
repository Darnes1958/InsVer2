@extends('PrnView.PrnMaster')

@section('mainrep')
  <div  >

    <div style="text-align: center">
      <label style="font-size: 10pt;">{{$tran_date}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >من تاريخ : </label>
      <label style="font-size: 10pt;">{{$item_name}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >حركة الصنف : </label>
    </div>


    <table   >
      <thead style="  margin-top: 8px;">
      <tr>
        <th style="width: 12%">طريقة الدفع</th>
        <th style="width: 12%">المجموع</th>
        <th style="width: 12%">السعر</th>
        <th style="width: 10%">الكمية</th>
        <th >اسم العميل</th>
        <th style="width: 12%">التاريخ</th>
        <th style="width: 12%">رقم الفاتورة</th>
        <th style="width: 12%">نوع الفاتورة</th>
      </tr>
      </thead>
      <tbody id="addRow" class="addRow">
      @foreach($RepTable as $key=> $item)
        <tr class="font-size-12">
          <td> {{ $item->type_name }} </td>
          <td> {{ $item->sub_tot }} </td>
          <td> {{ $item->price }} </td>
          <td> {{ $item->quant }} </td>
          <td> {{ $item->jeha_name }} </td>
          <td> {{ $item->order_date }} </td>
          <td> {{ $item->order_no }} </td>
          <td> {{ $item->order_type }} </td>
        </tr>
      @endforeach
      </tbody>
    </table>


  </div>



@endsection
