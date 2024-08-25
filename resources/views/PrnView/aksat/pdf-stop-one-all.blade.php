@extends('PrnView.PrnMasterOneAll')

@section('mainrep')
  @forelse($res as $key => $item)
    <div style=" position: fixed; right: 30px;">
      <label style="font-size: 20pt;" >{{$comp_name}}</label>
    </div>
    <div style=" position: fixed; right: 30px;">
      <br>
      <label style="font-size: 16pt;">{{$cus->CompanyNameSuffix}}</label>
    </div>
    <div style=" position: fixed; right: 600px;">
      <label> {{date('Y-m-d')}}التاريخ : </label>
    </div>

    <br>
    <br>

  <div style=" position: fixed;right: 80px; font-size: 16pt;">
    <br>

    <label > السادة المحترومون / {{$bank_name}}</label>

  </div>
    <br>

    <div style="position: fixed;right: 80px;">
      <br>
      <label style=" font-size: 16pt;">تحية طيبة </label>
      <br>
    </div>
<br>
<br>
  <div style="position: fixed; right: 80px; font-size: 14pt;">
    <br>
    <label >نأمل منكم إيقاف خصم الأقساط من حساب السيد / {{$item->name}}</label>
  </div>
    <br>
  <div style=" position: fixed;right: 80px; font-size: 14pt;">
    <br>

    <label>{{$item->kst}} حساب جاري رقم   {{$item->acc}}      وقيمة القسط  </label>
  </div>

    <br>

    <div style="position: fixed; right: 80px; font-size: 14pt;">
      <br>
      <label >اعتباراً من تاريخ {{$item->stop_date}}</label>
    </div>
    <br>

  <div style="position: fixed; right: 80px; font-size: 14pt;">

    <br>
    <label >لحساب الشركة التجميعي رقم   {{$TajAcc}}  مع رفع الحجز إن وجد </label>
  </div>
    <br>
    <br>

    <div style="position: fixed; right: 80px; font-size: 14pt;">

      <br>
     <label style=" font-size: 14pt;">نشكركم علي حسن تعاونكم  </label>

    </div>
<br>
  <div style="position: fixed; right: 160px;font-size: 14pt;">
    <br>
    <br>
    <label>والسلام عليكم ورحمة الله وبركاته</label>
    <br>


  </div>
    <br>
    <br>
  <br>
  <div style="position: fixed;right: 500px; font-size: 14pt;">
    <br>
    <br>
    <label>
      التوقيع ...................
    </label>

  </div>
   <br>


  <div style="position: fixed; right: 500px;font-size: 14pt;">
    <br>
    <br>
    <label>    مفوض الشركة /      {{$CompMan}}</label>
</div>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif

  @empty

  @endforelse
@endsection

