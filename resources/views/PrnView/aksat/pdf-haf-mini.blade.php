@extends('PrnView.PrnMaster')

@section('mainrep')
    <div  >
        <div style="text-align: center">
            <label style="font-size: 10pt;margin-right: 12px;">{{$who}}</label>
            <label style="font-size: 10pt;">{{$hafitha}}</label>
            <label style="font-size: 14pt;margin-right: 12px;" >حافظة رقم : </label>

            <label style="font-size: 10pt;">{{$bank_name}}</label>

            <label style="font-size: 14pt;margin-right: 12px;" >مصرف : </label>
            <label style="font-size: 16pt;">{{$kst_type_name}}</label>
        </div>
        <table   >
            <thead style="  margin-top: 8px;">
            <tr style="background:lightgray">
                <th style="width: 12%;">الباقي</th>
                <th style="width: 12%;">القسط</th>
                <th >الاسم</th>
                <th style="width: 16%;">رقم الحساب</th>
                <th style="width: 12%;">رقم العقد</th>
                <th style="width: 10%;">ت</th>
              </tr>
            </thead>
            <tbody >
           @foreach($RepTable as $key=>$item)
                <tr >
                    <td> {{ $item->baky }} </td>
                    <td> {{ $item->kst }} </td>
                    <td> {{ $item->name }} </td>
                    <td> {{ $item->acc }} </td>
                    <td > {{ $item->no }} </td>
                    <td > {{ $item->ser_in_hafitha }} </td>
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
