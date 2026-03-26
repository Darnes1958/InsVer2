@extends('PrnView.PrnMasterSpatie')

@section('mainrep')
    <div>

        <div style="text-align: center">
            <label style="font-size: 14pt;margin-right: 12px;color: #1e40af" >كشف بالمصروفات  </label>
            @if($arr['date'])
                <label style="font-size: 10pt;">{{$arr['date']}}</label>
            @endif

        </div>

        @if($arr['MasType'])
            <div >
                <label style="font-size: 14pt;margin-right: 12px;" >نوع المصروف : </label>
                <label style="font-size: 10pt;">{{$arr['MasType']}}</label>
            </div>
        @endif
        @if($arr['MasTypeDetail'])
            <div >
                <label style="font-size: 14pt;margin-right: 12px;" >تفاصيل المصروف : </label>
                <label style="font-size: 10pt;">{{$arr['MasTypeDetail']}}</label>
            </div>
        @endif
        @if($arr['MasCenter'])
            <div >
                <label style="font-size: 14pt;margin-right: 12px;" >مصروفة علي : </label>
                <label style="font-size: 10pt;">{{$arr['MasCenter']}}</label>
            </div>
        @endif

        <table style=" margin-left: 2%;margin-right: 2%; margin-bottom: 4%; margin-top: 2%;">
            <thead style="  margin-top: 8px;">
            <tr style="background: #9dc1d3;">
                <th style="width: 18%"> البيان</th>
                <th style="width: 18%">التفاصيل</th>
                <th style="width: 18%">مصروفة علي</th>
                <th style="width: 12%">التاريخ</th>
                <th style="width: 10%">المبلغ</th>
                <th >ملاحظات</th>
            </tr>
            </thead>
            <tbody id="addRow" class="addRow">
            @php $sumraseed=0; @endphp
            @foreach($res as $key=> $item)
                <tr >
                    <td> {{ $item->MasTypeTable->MasTypeName }} </td>
                    <td > {{ $item->MasTypeDetailTable->DetailName }} </td>
                    <td > {{ $item->MasCenterTable->CenterName }} </td>
                    <td style="text-align: center">{{$item->MasDate}}</td>
                    <td> {{ number_format($item->Val,0, '.', ',') }} </td>
                    <td> {{$item->Notes}} </td>
                </tr>
                @php $sumraseed+=$item->Val; @endphp
            @endforeach
            <tr class="font-size-12 " style="font-weight: bold; background: #9ca3af">

                <td style="font-weight:normal;">الإجمــــــــالي  </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> {{number_format($sumraseed, 0, '.', ',')}}  </td>
                <td>   </td>
            </tr>
            </tbody>
        </table>


    </div>



@endsection

