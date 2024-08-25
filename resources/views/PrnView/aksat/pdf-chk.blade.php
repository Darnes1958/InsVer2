

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
          size: 21cm 29.7cm ;
          margin: 8px;
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

  <div style="border: solid 1px;margin-bottom: 2pt; padding: 6px;line-height: 10px;">
    <div style="padding: 0;margin-bottom: 0px;">
      <label style="font-size: 10pt; margin-right: 12px; " >{{$cus->CompanyName}}</label>
    </div>
    <div style="padding: 0px;margin-top: 0px;">
      <label style="font-size: 8pt; margin-right: 12px;margin-bottom: 4px;">{{$cus->CompanyNameSuffix}}</label>
    </div>

  </div>

 <div style="border: solid 1px; margin-bottom: 2pt; padding-bottom: 10pt;">
  <div style="width: 100%; margin: auto;text-align: center;">
    <label style="font-size: 10pt;text-align: center;text-decoration: underline">ايصال استلام</label>

  </div>
  <div>

    <label style="font-size: 8pt;margin-right: 12px;text-decoration: underline;" >{{$name}}</label>
    <label style="font-size: 8pt;margin-right: 12px;width: 20%;" >تم تسليم الاخ </label>
  </div>
   <div>
     <label style="font-size: 8pt;margin-right: 12px; text-decoration: underline;" >{{$bank_name}}</label>
     <label style="font-size: 8pt;margin-right: 12px;width: 20%;" >التابع لمصـرف </label>
   </div>
   <div>
     <label style="font-size: 8pt;margin-right: 12px; text-decoration: underline;" >{{$acc}}</label>
     <label style="font-size: 8pt;margin-right: 12px;width: 20%;" >حســــاب رقـم </label>
   </div>
   <div>
     <label style="font-size: 8pt;margin-right: 12px; " >({{$chk_count}})</label>
     <label style="font-size: 8pt;margin-right: 12px;width: 20%;" >صكوك وعددها </label>
   </div>
   <div >
     <label style="font-size: 8pt;margin-right: 12px; text-decoration: underline;" >{{$wdate}}  </label>
     <label style="font-size: 8pt;margin-right: 12px;width: 20%;" >    بتاريـــــــــــخ   </label>
   </div>


 </div>
  <div style="border: solid 1px;margin-bottom: 2pt; padding: 6px;line-height: 10px;">
    <div style="padding: 0;margin-bottom: 0px; text-align: center">
      <label style="font-size: 8pt; margin-right: 12px; " >.........................................................</label>
      <label style="font-size: 8pt; margin-right: 12px; " >توقيع المستلم</label>

      <label style="font-size: 8pt; margin-right: 12px; " >.........................................................</label>
      <label style="font-size: 8pt; margin-right: 12px; " >توقيع المسلم</label>

    </div>

  </div>
</body>
</html>
