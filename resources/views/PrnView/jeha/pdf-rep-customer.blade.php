@extends('PrnView.PrnMaster')

@section('mainrep')



  <div >

    <label style="font-size: 14pt;margin-right: 12px;" >تقرير بأرصدة الزبائن (ليس لديهم تقسيط) </label>
  </div>


  <table  width="100%"   align="right" >

    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
      <th style="width: 14%">الرصيد</th>
      <th style="width: 14%">دفع</th>
      <th style="width: 14%">قبض</th>
      <th style="width: 14%">مبيعات</th>
      <th >اسم الزبون</th>
      <th style="width: 12%">رقم الزبون</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">

    @foreach($res as $key => $item)
      <tr >
       <td> {{ number_format($item->differ,2, '.', ',') }} </td>
       <td> {{ number_format($item->ValExp,2, '.', ',') }} </td>
       <td> {{ number_format($item->ValImp,2, '.', ',') }} </td>
       <td> {{ number_format($item->tot,2, '.', ',') }} </td>
       <td > {{ $item->jeha_name }} </td>
       <td> {{ $item->jeha_no }} </td>
      </tr>
      <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
        <label class="page"></label>
        <label> صفحة رقم </label>
      </div>
    @endforeach
    </tbody>
      <tfoot>
      <tr style=" font-family: DejaVu Sans, sans-serif; background: #9dc1d3;">
          <th style="font-weight: bold">{{ number_format($Sum,2, '.', ',') }}</th>

          <th style="font-weight: bold">الاجمالي</th>

          <th></th>
          <th></th>
          <th></th>
          <th></th>

      </tr>
      </tfoot>

  </table>



@endsection
