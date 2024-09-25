<div>
    @if(\Illuminate\Support\Facades\Auth::id()==1)
        <span style="color: #a3e635">{{\Illuminate\Support\Facades\Auth::user()->company}}</span>
    @endif

</div>
