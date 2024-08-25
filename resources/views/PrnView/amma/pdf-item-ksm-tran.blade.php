@extends('PrnView.PrnMaster')

@section('mainrep')
  <div  >

    <div style="text-align: center">
      <label style="font-size: 10pt;">{{$tran_date}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >من تاريخ : </label>
      <label style="font-size: 10pt;">{{$item_name}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >الأقساط المحصلة من الصنف : </label>
    </div>


    <table   >
      <thead style="  margin-top: 8px;">
      <tr>
        <th style="width: 10%">القسط</th>
        <th style="width: 12%">التاريخ</th>
        <th >الإسم</th>
        <th style="width: 16%">رقم الحساب</th>
        <th style="width: 12%">رقم العقد</th>
      </tr>
      </thead>
      <tbody id="addRow" class="addRow">
      @foreach($RepTable as $key=> $item)
        <tr class="font-size-12">
          <td> {{ number_format($item->ksm,2, '.', ',') }}  </td>
          <td> {{ $item->ksm_date }} </td>
          <td> {{ $item->name }} </td>
          <td> {{ $item->acc }} </td>
          <td> {{ $item->no }} </td>
        </tr>
      @endforeach
      <tr  class="font-size-12 " style="line-height: 20px;background: lightgray">
        <td style="font-weight: bold"> {{ number_format($SumKsm,2, '.', ',') }} </td>
        <td style="font-weight: bold ;font-family: DejaVu Sans, sans-serif"> الإجمالي </td>
        <td>  </td>
        <td>  </td>
        <td>  </td>
      </tr>
      </tbody>
    </table>


  </div>



@endsection
