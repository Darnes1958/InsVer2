

<!doctype html>

<html lang="ar" dir="rtl">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet" />
  <style>
      #content {
          display: table;
      }
      body {
          counter-increment: pageplus1 page;
          counter-reset: pageplus1 1;
          direction: rtl;
          font-family: Amiri ;
      }


      #footer {
          height: 30px;
          position: fixed;
          margin: 5px;
          bottom: 0;
          text-align: center;
      }
      #footer .page:after {
          content: counter(page);
      }
      #footer .pageplus1:after {
          content:  counter(pageplus1);
      }
      @page {
          size: 29.7cm 21cm;
          margin: 4px;
      }
      table {
          width: 96%;
          border-collapse: collapse;
          border: 1pt solid  lightgray;
          margin-right: 12px;
          font-size: 12px;
      }
      tr {
          border: 1pt solid  lightgray;
      }
      th {
          text-align: center;
          border: 1pt solid  lightgray;
          font-size: 12px;
      }
      td {
          text-align: right;
          border: 1pt solid  lightgray;
      }
  </style>
</head>
<body  >
<div  >
    <label style="font-size: 24pt; margin-right: 12px;" >{{$cus->CompanyName}}</label>
    <br>
        <label style="font-size: 18pt; margin-right: 12px;">{{$cus->CompanyNameSuffix}}</label>
    <br>
    <br>

    <div >
        <label style="font-size: 10pt;">{{$bank_name}}</label>
        <label style="font-size: 14pt;margin-right: 12px;" >المصرف : </label>
    </div>


  <table  width="100%"   align="right" >
      <caption style="font-size: 14pt; margin: 8px;">{{'كشف بالعقود المسددة بتاريخ '.$RepDate }} </caption>
    <thead style=" font-family: DejaVu Sans, sans-serif; margin-top: 8px;" >
    <tr  style="background: #9dc1d3;" >
        <th style="width: 7%">المطلوب</th>
        <th style="width: 7%">المسدد</th>
        <th style="width: 7%">القسط</th>
        <th style="width: 7%">ع.الاقساط</th>
        <th style="width: 7%"> ج.التقسيط</th>
        <th style="width: 7%">دفعة</th>
        <th style="width: 7%">ج.الفاتورة</th>
        <th style="width: 7%">ت.العقد</th>
        <th >الاسم</th>
        <th style="width: 14%">رقم الحساب</th>
        <th style="width: 8%">رقم العقد</th>
    </tr>
    </thead>
    <tbody style="margin-bottom: 40px; ">
    @foreach($pdfdetail as $key => $item)
      <tr >
          <td> {{ $item->raseed }} </td>
          <td> {{ $item->sul_pay }} </td>
          <td> {{ $item->kst }} </td>
          <td style="text-align: center"> {{ $item->kst_count }} </td>
          <td> {{ $item->sul }} </td>
          <td> {{ $item->dofa }} </td>
          <td> {{ $item->sul_tot }} </td>
          <td style="text-align: center;"> {{ $item->sul_date }} </td>
          <td> {{ $item->name }} </td>
          <td> {{ $item->acc }} </td>
          <td style="text-align: center;"> {{ $item->no }} </td>
      </tr>
      <div id="footer" style="height: 50px; width: 100%; margin-bottom: 0px; margin-top: 10px;
                              display: flex;  justify-content: center;">
          <label class="page"></label>
          <label> صفحة رقم </label>
      </div>
    @endforeach
    </tbody>
      <tbody>
      <tr  >
          <td style="font-weight: bold;text-align: right;">{{$sum->raseed}}</td>
          <td style="font-weight: bold;text-align: right;">{{$sum->sul_pay}}</td>
          <td > </td>
          <td ></td>
          <td style="font-weight: bold;text-align: right;">{{$sum->sul}}</td>
          <td style="font-weight: bold;text-align: right;">{{$sum->dofa}}</td>
          <td style="font-weight: bold;text-align: right;">{{$sum->sul_tot}}</td>

          <td  style="border-right: none;text-align: center">الاجمالي</td>
          <td style="border-left: none;border-right: none;"></td>
          <td style="border-left: none;border-right: none;"></td>
          <td style="border-left: none;border-right: none;"></td>
      </tr>


      </tbody>
  </table>
</div>
</div>
</body>
</html>
