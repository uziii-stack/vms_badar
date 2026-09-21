@auth
@extends('layouts.layout')
@section("content")
@if(session()->get('user')->roles[0]->name =="admin" || session()->get('user')->roles[0]->name =="media" || session()->get('user')->roles[0]->name =="bxssUser")
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">Programs</h5>
                    </div>
                </div>
                <div id="programChart"></div>
            </div>
        </div>
    </div>
</div>
@if(session()->get('user')->roles[0]->name =="admin" || session()->get('user')->roles[0]->name =="bxssUser")
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">Coupons</h5>
                    </div>
                </div>
                <div id="couponsChart"></div>
            </div>
        </div>
    </div>
</div>
@endif
@if(session()->get('user')->roles[0]->name =="admin" || session()->get('user')->roles[0]->name =="bxssUser")
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">HR</h5>
                    </div>
                </div>
                <div id="hrChart"></div>
            </div>
        </div>
    </div>
</div>
@endif
@elseif(session()->get('user')->roles[0]->name =="orgRep")
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        @foreach(\App\Models\Organization::where('uid',session()->get('user')->uid)->get() as $organization)
                        <h5 class="card-title fw-semibold">{{$organization->company_name}}</h5>
                        @endforeach
                    </div>
                </div>
                <div id="orgRepchart"></div>
            </div>
        </div>
    </div>
</div>
@elseif(session()->get('user')->roles[0]->name =="mediaRep")
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        @foreach (\App\Models\MediaGroup::where('uid',session()->get('user')->uid)->get() as $media)
                        <h5 class="card-title fw-semibold">{{$media->media_name}}</h5>
                        @endforeach
                    </div>
                </div>
                <div id="mediaRepchart"></div>
            </div>
        </div>
    </div>
</div>
@elseif(session()->get('user')->roles[0]->name =="hrRep")
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                    <div class="mb-3 mb-sm-0">
                        @foreach (\App\Models\HrGroup::where('uid',session()->get('user')->uid)->get() as $hr)
                        <h5 class="card-title fw-semibold">{{$hr->hr_name}}</h5>
                        @endforeach
                    </div>
                </div>
                <div id="hrRepchart"></div>
            </div>
        </div>
    </div>
</div>
<br />
@endif
<script defer>
    axios.get('/api/snseaProgramStats')
        .then(function(response) {
            let programStats = response.data;
            let categories = programStats.map(val => val.program_name);
            let staffCounts = programStats.map(val => Number(val.staff_count) || 0);

            var chartOptions1 = {
                series: [{
                    name: 'Staff Count',
                    data: staffCounts
                }],
                chart: {
                    type: "bar",
                    height: 345
                },
                xaxis: {
                    categories
                },
                yaxis: {
                    max: Math.max(...staffCounts) + 2
                }
            };

            new ApexCharts(document.querySelector("#programChart"), chartOptions1).render();
        });

    axios.get('/api/snseaCouponStats')
        .then(function(response) {
            let couponsStats = response.data || [];

            let days = couponsStats.map(val => val.coupon_day || '');
            let staffCounts = couponsStats.map(val => Number(val.staff_count) || 0);
            let programs = couponsStats.map(val => val.coupon_name || '');

            var chartOptions2 = {
                series: [{
                    name: 'Staff Count',
                    data: staffCounts
                }],
                chart: {
                    type: "bar",
                    height: 345
                },
                xaxis: {
                    categories: days,
                    title: {
                        text: 'Day'
                    }
                },
                yaxis: {
                    min: 0,
                    max: staffCounts.length ? Math.max(...staffCounts) + 2 : 10
                },
                tooltip: {
                    y: {
                        formatter: (val, opts) => `${val} staff (${programs[opts.dataPointIndex]})`
                    }
                }
            };

            new ApexCharts(document.querySelector("#couponsChart"), chartOptions2).render();
        })
        .catch(function(error) {
            console.error('Error loading coupon stats:', error);
        });
</script>
@endsection
@endauth