<div class="badge-parent row badge-print-{{$componentKey}}"
    style="height:{{$componentKey==0?'300px':'300px'}}; margin-top:{{$componentKey==0?'20px':'70px'}};">
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <div class="d-flex align-items-end">
        <div class="card-border">
            <div class="logo-child">
                <div>
                    <h5 class="text-left mx-3 my-0" style="font-weight: 500; overflow: visible;">
                        @foreach (\App\Models\Rank::where('id',$badgeData->depo_guest_rank)->get() as $key=>$rank)
                        {{$rank->ranks_name}}
                        @endforeach
                        {{$badgeData->staff_first_name}} {{$badgeData->staff_last_name}}
                    </h5>
                    <h6 class="text-left mx-3 my-0" style="font-weight: 500; overflow: visible;">
                        {{$badgeData->staff_designation}}
                    </h6>
                    <h6 class="text-left mx-3 my-0" style="font-weight: 500; overflow: visible;">
                        @if($badgeData->companyName?->company_name != 'sulemansBulk')
                        {{$badgeData->companyName?->company_name}}
                        @else
                        {{isset($badgeData->address)?$badgeData->address:''}}
                        @endif
                    </h6>
                </div>
            </div>
            <div class="d-flex my-1">
                <div id="barCode-{{$componentKey}}" class="barcode-list" custom-id="{{$badgeData->code}}"></div>
            </div>
            <div class="card-border">
                <div class="logo-child">
                    <div>
                        <h6 class="text-left mx-3" style="font-weight: 500; overflow: visible;">
                            {{$badgeData->staff_identity}}/{{$badgeData->code}}/{{$badgeData->staff_country}}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        @if($badgeData->image)
        <div class="card-border mx-2 mb-2">
            <div class="logo-child mx-4">
                <img src="{{asset('storage/images/'. $badgeData->uid . '.png') . '?t=' . time() ;}}"
                    style="height: 120px; width: 100px;" class="img-fluid" alt="Picture"
                    onerror="this.style.display='none'" />
            </div>
        </div>
        @endif
    </div>
</div>