@extends('PrnView.PrnMasterSpatie')

@section('mainrep')
    <div>

        <div style="text-align: center">
            <label style="font-size: 14pt;margin-right: 12px;color: #1e40af" >اجمالي بنود المصروفات  </label>
            @if($arr['date'])
                <label style="font-size: 10pt;">{{$arr['date']}}</label>
            @endif

        </div>


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
                <th style="width: 10%">المبلغ</th>
            </tr>
            </thead>
            <tbody id="addRow" class="addRow">
            @php $sumraseed=0; @endphp
            @foreach($res as $key=> $item)
                <tr >
                    <td> {{ $item->MasTypeName }} </td>
                    <td> {{ number_format($item->Val,0, '.', ',') }} </td>
                </tr>
                @php $sumraseed+=$item->Val; @endphp
            @endforeach
            <tr class="font-size-12 " style="font-weight: bold; background: #9ca3af">

                <td style="font-weight:normal;">الإجمــــــــالي  </td>

                <td> {{number_format($sumraseed, 0, '.', ',')}}  </td>

            </tr>
            </tbody>
        </table>


    </div>



@endsection

