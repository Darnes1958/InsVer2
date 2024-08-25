@extends('PrnView.PrnMaster')

@section('mainrep')



  <div >

    <label style="font-size: 14pt;margin-right: 12px;" >تقرير بجميع الزبائن </label>
  </div>


  <table  width="100%"   align="right" >

    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >


      <th style="width: 40%">ملاحظات</th>
      <th >اسم الزبون</th>
      <th style="width: 12%">رقم الزبون</th>


    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">

    @foreach($res as $key => $item)
      <tr >
          <td> </td>
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

  </table>



@endsection
