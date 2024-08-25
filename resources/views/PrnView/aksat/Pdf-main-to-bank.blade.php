@extends('PrnView.PrnMaster')

@section('mainrep')

    <div style="position: fixed; text-align: center;  width: 100%;  margin: 10px;
                              display: flex;  justify-content: center;">
      <label style="font-size: 14pt;">{{$TajNo}}</label>
      <label  style="width: 20%;font-size: 14pt;">رقم الحساب التجميعي</label>
    </div>
    <br>
    <br>
    <table style=" border: none;margin-bottom: 5px; padding-right: 5%">
        <tbody >
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 2%; ">  </td>
                <td class="order-td" style="width: 30%;"> {{$res->name}} </td>
                <td style="border: none;width: 15%;font-size: 14pt; "> اسم الزبون </td>
            </tr>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 15%; ">  </td>
                <td class="order-td" style="width: 15%; text-align: center"> {{$res->acc}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> رقم الحساب </td>
            </tr>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 2%; ">  </td>
                <td class="order-td" style="width: 15%;"> {{$res->kst}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> القسط </td>
            </tr>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 30%; ">  </td>
                <td class="order-td" style="width: 15%; text-align: center"> {{$res->kst_count}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> عدد الأقساط </td>
            </tr>
            <tr style="border: none; line-height: 18px;">
                <td style="border: none;width: 2%; ">  </td>
                <td class="order-td" style="width: 15%;"> {{$res->raseed}} </td>
                <td style="border: none;width: 12%;font-size: 12pt; "> المطلوب </td>
            </tr>


        </tbody>

    </table>

    <br>

<table>
    <tbody>
    <tr style="border: none; line-height: 18px;">
        <td style="border: none;width: 2%; ">  </td>

        <td style="border: none;width: 100%;font-size: 12pt; "> هل تم إدخال العقد أم لا ؟ </td>
    </tr>
    <tr style="border: none; line-height: 18px;">
        <td style="border: none;width: 2%; ">  </td>


        <td style="border: none;width: 100%;font-size: 12pt; "> في حال عدم ادخال العقد نامل ذكر الاسباب ؟ </td>
    </tr>
    </tbody>
</table>
    <br>
    <br>
    <table>
        <tbody>
        <tr style="border: none; line-height: 20px;">
            <td style="border: none;width: 2%; ">  </td>

            <td style="border: none;text-align: center;width: 100%;font-size: 12pt; "> نشكركم سلفا علي حسن تعاونكم </td>
            <td style="border: none;width: 2%; ">  </td>
        </tr>
        <tr style="border: none; line-height: 20px;">
            <td style="border: none;width: 2%; ">  </td>

            <td style="border: none;width: 100%;text-align: center;font-size: 12pt; "> وتقبلوا منا فائق الإحترام والتقدير </td>
            <td style="border: none;width: 2%; ">  </td>
        </tr>

        </tbody>
    </table>

@endsection







