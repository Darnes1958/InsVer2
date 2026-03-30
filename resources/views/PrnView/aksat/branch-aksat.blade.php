@extends('PrnView.PrnMasterSpatie')

@section('mainrep')
    <div>

        <div style="text-align: center">
            <label style="font-size: 14pt;margin-right: 12px;color: #1e40af" >كشف بالأقساط المحصلة بحسب الفروع   </label>
            @if($arr['date'])
                <label style="font-size: 10pt;">{{$arr['date']}}</label>
            @endif

        </div>


            <div >
                <label style="font-size: 14pt;margin-right: 12px;" >للمصرف التجميعي : </label>
                <label style="font-size: 10pt;">{{$arr['TajName']}}</label>
            </div>



        <table style=" margin-left: 2%;margin-right: 2%; margin-bottom: 4%; margin-top: 2%;">
            <thead style="  margin-top: 8px;">
            <tr style="background: #9dc1d3;">
                <th style="width: 18%"> الفرع</th>
                <th style="width: 20%">اجمالي الاقساط المخصومة</th>
                <th style="width: 20%">عدد الاقساط</th>

            </tr>
            </thead>
            <tbody id="addRow" class="addRow">
            @php $sumaksat=0;$sumcount=0 @endphp
            @foreach($res as $key=> $item)
                <tr >
                    <td> {{ $item->hall_name }} </td>
                    <td > {{number_format($item->ksmValue,0,'.',',') }} </td>
                    <td > {{number_format($item->ksmCount,0,'.',',') }} </td>
                </tr>
                @php $sumaksat+=$item->ksmValue; $sumcount+=$item->ksmCount;@endphp
            @endforeach
            <tr class="font-size-12 " style="font-weight: bold; background: #9ca3af">

                <td style="font-weight:normal;">الإجمــــــــالي  </td>
                <td> {{number_format($sumaksat, 0, '.', ',')}}  </td>
                <td> {{number_format($sumcount, 0, '.', ',')}}  </td>

            </tr>
            </tbody>
        </table>


    </div>



@endsection

