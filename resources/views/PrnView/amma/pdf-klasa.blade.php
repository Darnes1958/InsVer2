@extends('PrnView.PrnMaster')

@section('mainrep')
  <div  >

    <div style="text-align: center">
      <label style="font-size: 10pt;">{{$date2}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >إلي تاريخ : </label>
      <label style="font-size: 10pt;">{{$date1}}</label>
      <label style="font-size: 14pt;margin-right: 12px;" >خلاصة الحركة اليومية   من تاريخ : </label>
    </div>

    <label style="font-size: 14pt;margin-right: 12px;" >مشتريات </label>
    <table  width="100%"   align="right" >
      <thead style="  margin-top: 8px;">
      <tr style="background:lightgray">
        <th style="width: 12%;">الباقي</th>
        <th style="width: 12%;">المدفوع</th>
        <th style="width: 14%;">الإجمالي النهائي</th>
        <th style="width: 12%;">الخصم</th>
        <th style="width: 12%;">الإجمالي</th>
        <th style="width: 12%;">طريقة الدفع</th>
        <th >المخزن</th>
      </tr>
      </thead>
      <tbody id="addRow" class="addRow">
      @php $sumtot1=0;$sumksm=0;$sumtot=0;$sumcash=0;$sumnot_cash=0; @endphp
      @foreach($BuyTable as $key=>$item)
        <tr class="font-size-12">
          <td> {{number_format($item->not_cash, 2, '.', ',')}} </td>
          <td> {{number_format($item->cash, 2, '.', ',')}} </td>
          <td> {{number_format($item->tot, 2, '.', ',')}} </td>
          <td> {{number_format($item->ksm, 2, '.', ',')}} </td>
          <td> {{number_format($item->tot1, 2, '.', ',')}} </td>
          <td> {{$item->type_name}}  </td>
          <td >{{$item->place_name}}  </td>
        </tr>
        <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
        </div>
        @php $sumtot1+=$item->tot1;$sumksm+=$item->ksm;$sumtot+=$item->tot;
                 $sumcash+=$item->cash;$sumnot_cash+=$item->not_cash; @endphp
      @endforeach
      <tr class="font-size-12 " style="font-weight: bold">
        <td> {{number_format($sumnot_cash, 2, '.', ',')}} </td>
        <td> {{number_format($sumcash, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot, 2, '.', ',')}} </td>
        <td> {{number_format($sumksm, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot1, 2, '.', ',')}} </td>
        <td>   </td>
        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      </tr>

      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>

      </tbody>
    </table>


    <label style="font-size: 14pt;margin-right: 12px;" >مبيعات مخازن </label>
    <table  width="100%"   align="right" >
      <thead style="  margin-top: 8px;">
      <tr style="background:lightgray">
        <th style="width: 12%;">الباقي</th>
        <th style="width: 12%;">المدفوع</th>
        <th style="width: 14%;">الإجمالي النهائي</th>
        <th style="width: 12%;">الخصم</th>
        <th style="width: 12%;">الإجمالي</th>
        <th style="width: 12%;">طريقة الدفع</th>
        <th >المخزن</th>
      </tr>
      </thead>
      <tbody id="addRow" class="addRow">
      @php $sumtot1=0;$sumksm=0;$sumtot=0;$sumcash=0;$sumnot_cash=0;
           $sumtot1_all=0;$sumksm_all=0;$sumtot_all=0;$sumcash_all=0;$sumnot_cash_all=0;
      @endphp
      @foreach($SellTableMak as $key=>$item)
        <tr class="font-size-12">
          <td> {{number_format($item->not_cash, 2, '.', ',')}} </td>
          <td> {{number_format($item->cash, 2, '.', ',')}} </td>
          <td> {{number_format($item->tot, 2, '.', ',')}} </td>
          <td> {{number_format($item->ksm, 2, '.', ',')}} </td>
          <td> {{number_format($item->tot1, 2, '.', ',')}} </td>
          <td> {{$item->type_name}}  </td>
          <td >{{$item->place_name}}  </td>
        </tr>
        <div id="footer" style=" width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
        </div>
        @php $sumtot1+=$item->tot1;$sumksm+=$item->ksm;$sumtot+=$item->tot;
                 $sumcash+=$item->cash;$sumnot_cash+=$item->not_cash; @endphp
      @endforeach
      @php
          $sumtot1_all+=$sumtot1;$sumksm_all+=$sumksm;$sumtot_all+=$sumtot;
                 $sumcash_all+=$sumcash;$sumnot_cash_all+=$sumnot_cash;
      @endphp
      <tr class="font-size-12 " style="font-weight: bold">
        <td> {{number_format($sumnot_cash, 2, '.', ',')}} </td>
        <td> {{number_format($sumcash, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot, 2, '.', ',')}} </td>
        <td> {{number_format($sumksm, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot1, 2, '.', ',')}} </td>
        <td>   </td>
        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      </tr>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>

      </tbody>
    </table>


    <label style="font-size: 14pt;margin-right: 12px;" >مبيعات صالات </label>
    <table  width="100%"   align="right" >
      <thead style="  margin-top: 8px;">
      <tr style="background:lightgray">
        <th style="width: 12%;">الباقي</th>
        <th style="width: 12%;">المدفوع</th>
        <th style="width: 14%;">الإجمالي النهائي</th>
        <th style="width: 12%;">الخصم</th>
        <th style="width: 12%;">الإجمالي</th>
        <th style="width: 12%;">طريقة الدفع</th>
        <th >الصالة</th>
      </tr>
      </thead>
      <tbody id="addRow" class="addRow">
      @php $sumtot1=0;$sumksm=0;$sumtot=0;$sumcash=0;$sumnot_cash=0; @endphp
      @foreach($SellTableSalat as $key=>$item)
        <tr class="font-size-12">
          <td> {{number_format($item->not_cash, 2, '.', ',')}} </td>
          <td> {{number_format($item->cash, 2, '.', ',')}} </td>
          <td> {{number_format($item->tot, 2, '.', ',')}} </td>
          <td> {{number_format($item->ksm, 2, '.', ',')}} </td>
          <td> {{number_format($item->tot1, 2, '.', ',')}} </td>
          <td> {{$item->type_name}}  </td>
          <td >{{$item->place_name}}  </td>
        </tr>

        @php $sumtot1+=$item->tot1;$sumksm+=$item->ksm;$sumtot+=$item->tot;
                 $sumcash+=$item->cash;$sumnot_cash+=$item->not_cash; @endphp
      @endforeach
      @php
          $sumtot1_all+=$sumtot1;$sumksm_all+=$sumksm;$sumtot_all+=$sumtot;
                 $sumcash_all+=$sumcash;$sumnot_cash_all+=$sumnot_cash;
      @endphp
      <tr class="font-size-12 " style="font-weight: bold">
        <td> {{number_format($sumnot_cash, 2, '.', ',')}} </td>
        <td> {{number_format($sumcash, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot, 2, '.', ',')}} </td>
        <td> {{number_format($sumksm, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot1, 2, '.', ',')}} </td>
        <td>   </td>
        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      </tr>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>

      <div id="footer" style=" width: 100%;  margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">

        <label class="page" ></label>
        <label > صفحة رقم </label>
      </div>
      </tbody>
    <tbody id="addRow" class="addRow">
    <tr ><td style="border: none"></td><td style="border: none"></td><td style="border: none"></td>
        <td style="border: none"></td><td style="border: none"></td><td style="border: none"></td><td style="border: none"></td>
    <tr ><td style="border: none"></td><td style="border: none"></td><td style="border: none"></td>
        <td style="border: none"></td><td style="border: none"></td><td style="border: none"></td><td style="border: none"></td>
    </tr>
    <tr  style="font-weight: bold;background: lightgray;font-size: 10pt;">
        <td> {{number_format($sumnot_cash_all, 2, '.', ',')}} </td>
        <td> {{number_format($sumcash_all, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot_all, 2, '.', ',')}} </td>
        <td> {{number_format($sumksm_all, 2, '.', ',')}} </td>
        <td> {{number_format($sumtot1_all, 2, '.', ',')}} </td>
        <td>   </td>
        <td style="font-family: DejaVu Sans, sans-serif">اجمالي المبيعات   </td>
    </tr>
    </tbody>

    </table>

    <div class="page-break"></div>

    <label style="font-size: 14pt;margin-right: 12px;" >ايصالات قبض </label>
    <table style=" width:60%"   align="right" >
      <thead style="  margin-top: 8px;">
      <tr style="background:lightgray">
        <th style="width: 30%;">الاجمالي</th>
        <th style="width: 35%;">طريقة الدفع</th>
        <th style="width: 35%;">نوع الإيصال</th>
      </tr>
      </thead>
      <tbody >
      @php $sumval=0 @endphp
      @foreach($TransTableImp as $key=>$item)
        <tr class="font-size-12">
          <td> {{number_format($item->val, 2, '.', ',')}} </td>
          <td> {{$item->type_name}}  </td>
          <td >{{$item->who_name}}  </td>
        </tr>
        <div id="footer" style=" width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
        </div>
        @php $sumval+=$item->val; @endphp
      @endforeach
      <tr class="font-size-12 " style="font-weight: bold">
        <td> {{number_format($sumval, 2, '.', ',')}} </td>
        <td>   </td>
        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      </tr>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      </tbody>
    </table>
    <label style="font-size: 14pt;margin-right: 12px;" >ايصالات دفع </label>
    <table style=" width:60%"   align="right" >
      <thead style="  margin-top: 8px;">
      <tr style="background:lightgray">
        <th style="width: 30%;">الاجمالي</th>
        <th style="width: 35%;">طريقة الدفع</th>
        <th style="width: 35%;">نوع الإيصال</th>
      </tr>
      </thead>
      <tbody >
      @php $sumval=0 @endphp
      @foreach($TransTableExp as $key=>$item)
        <tr class="font-size-12">
          <td> {{number_format($item->val, 2, '.', ',')}} </td>
          <td> {{$item->type_name}}  </td>
          <td >{{$item->who_name}}  </td>
        </tr>
        <div id="footer" style=" width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
        </div>
        @php $sumval+=$item->val; @endphp
      @endforeach
      <tr class="font-size-12 " style="font-weight: bold">
        <td> {{number_format($sumval, 2, '.', ',')}} </td>
        <td>   </td>
        <td style="font-weight:normal;">الإجمــــــــالي  </td>
      </tr>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      <td style="border-bottom: none;border-left: none;border-right: none;"> </td>
      </tbody>
    </table>


  </div>



@endsection
