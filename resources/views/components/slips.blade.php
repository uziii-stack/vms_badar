<div>
    @if($attandees)
    <link rel="stylesheet" href="{{asset('assets/css/styles.min.css')}}" />
    <div class="slipComponent">
        <img src="{{asset('images/icons/PIMEC-2025.png') . '?t=' . time() }}" width="180" class="d-block mx-auto"
            alt="PIMEC 2025 Logo">
        @if(isset($attandees['deadline']))
        <p class="text-center">{{date("Y-m-d")}} : {{date("H:i:s")}}</p>
        <h2 class="text-center">{{$attandees['name']}}</h2>
        {{-- <h3 class="text-center">{{$attandees['status'] == 1?'Active':'InActive'}}</h3> --}}
        <h3 class="text-center">{{$attandees['organisation']}}</h3>
        <h3 class="text-center">{{$attandees['identity']}}</h3>
        <div class="d-flex my-1 justify-content-center">
            <div id="barCode" class="barcode-list" custom-id="{{$attandees['code']}}"></div>
        </div>
        <p class="text-center">Validity : {{$attandees['deadline']}}</p>
        <p class="text-center">This is a Temporary Pass</p>
        <p class="text-center">Powered by Badar Expo Solutions</p>
        @else
        <p class="text-center">{{date("Y-m-d")}} : {{date("H:i:s")}}</p>
        <h2 class="text-center">{{$attandees['name']}}</h2>
        <h3 class="text-center">{{$attandees['designation']}}</h3>
        <h3 class="text-center">{{$attandees['company']}}</h3>
        <p class="text-center">{{$attandees['nationality']}}</p>
        <p class="text-center">{{$attandees['identity']}}</p>
        <div class="d-flex my-1 justify-content-center">
            <div id="barCode" class="barcode-list" custom-id="{{$attandees['code']}}"></div>
        </div>
        <p class="text-center">You can collect your badge from next counter by presenting this Slip</p>
        <p class="text-center">Powered by Badar Expo Solutions</p>
        @endif
        <br />
    </div>
    @endif
</div>