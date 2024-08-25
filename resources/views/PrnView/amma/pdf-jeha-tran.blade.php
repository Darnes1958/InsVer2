@extends('PrnView.PrnMaster')

@section('mainrep')
  <div  >

    <div style="text-align: center ; margin-bottom: 5px;">
      <label style="font-size: 10pt;">{{$tran_date}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >من تاريخ : </label>
      <label style="font-size: 10pt;">{{$jeha_name}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >كشف حساب العميل : </label>
    </div>


    <table style="border-collapse:collapse;width: 100%"  >
      <thead >

      <tr style="background:lightgray ;">
        <th style="font-size: 7pt;">ملاحظات</th>
        <th style="width: 10%;font-size: 7pt;">طريقة الدفع</th>
        <th style="width: 10%;font-size: 7pt;">رقم المستند</th>
        <th style="width: 10%;font-size: 7pt;">الرصيد</th>
        <th style="width: 10%;font-size: 7pt;">دائن</th>
        <th style="width: 10%;font-size: 7pt;">مدين</th>

        <th style="width: 12%;font-size: 7pt;">التاريخ</th>
        <th style="font-size: 7pt;">البيان</th>
      </tr>
      </thead>
      <tbody style="margin-bottom: 40px; ">
      <tr  >
        <td>  </td>
        <td>  </td>
        <td>  </td>
        @if($Alraseed<0)
         <td style="color: red"  > {{ number_format($Alraseed,2, '.', ',') }} </td>
        @else
          <td style="color: blue" > {{ number_format($Alraseed,2, '.', ',') }} </td>
        @endif
        <td style="color: blue"> {{ number_format($DaenBefore,2, '.', ',') }} </td>
        <td style="color: red"> {{ number_format($MdenBefore,2, '.', ',') }} </td>

        <td>  رصيد سابق</td>
        <td>  </td>
      </tr>

      @foreach($RepTable as $key=>$item)
        @php $Alraseed+=$item->daen-$item->mden; @endphp
        <tr style="border:1px solid ;">
          <td> {{ $item->notes }} </td>
          @if($item->type_name=='مشتريات')
            <td></td>
          @else
          <td> {{ $item->type_name }} </td>
          @endif
          <td> {{ $item->order_no }} </td>
          @if ($Alraseed<0)
          <td style="color: red"  > {{ number_format($Alraseed,2, '.', ',') }} </td>
          @else
            <td style="color: blue" > {{ number_format($Alraseed,2, '.', ',') }} </td>
          @endif

          <td> {{ $item->daen }} </td>
          <td> {{ $item->mden }} </td>

          <td> {{ $item->order_date }} </td>
          <td> {{ $item->data }} </td>
        </tr>

        <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">

          <label class="page"></label>
          <label> صفحة رقم </label>
        </div>

      @endforeach

      <tr  >
        <td>  </td>
        <td>  </td>
        <td>  </td>
        @if ($Alraseed<0)
          <td style="color: red"  > {{ number_format($Alraseed,2, '.', ',') }} </td>
        @else
          <td style="color: blue" > {{ number_format($Alraseed,2, '.', ',') }} </td>
        @endif

        <td style="color: blue"> {{ number_format($Daen,2, '.', ',') }} </td>
        <td style="color: red"> {{ number_format($Mden,2, '.', ',') }} </td>
        <td>  الإجمالي</td>
        <td>  </td>
      </tr>


      </tbody>
    </table>


  </div>



@endsection
