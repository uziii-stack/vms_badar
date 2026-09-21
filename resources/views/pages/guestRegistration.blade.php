@extends('layouts.layout')
@section("content")
<style>
    .gradient-class {
        background: #2BC0E4;
        background: -webkit-linear-gradient(to right, #EAECC6, #2BC0E4);
        background: linear-gradient(to right, #EAECC6, #2BC0E4);
    }
</style>
<div class="page-wrapper gradient-class notPrintable" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div
        class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100 notPrintable">
            <div class="row justify-content-center w-100 notPrintable">
                <div class="col-md-8 col-lg-6 col-xxl-3 notPrintable">
                    <div class="card mb-0" style="border-radius: 30px;box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;">
                        <div class="card-body notPrintable">
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div>{{session('error')}}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @elseif(session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <div>{{session('message')}}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            <a href="{{route('pages.dashboard')}}"
                                class="text-nowrap logo-img text-center d-block py-3 w-100">
                                <img src="{{asset('images/icons/'.$event.'.png') . '?t=' . time() }}" width="180" alt="{{$event}} Logo">
                            </a>
                            {{-- <h3 class="text-center">This registration is only for foreign visitors. The Registration for National visitors will be announced later.</h3> --}}
                            <livewire:self-registration-component :isInternational="false" :isNew='true' :visitorUid='""'
                                eventName="{{$event}}" />
                            <br />
                            @if($event=='PIMEC-2025')
                            <img style="width:445px;width:-webkit-fill-available;"
                                src="{{asset('images/icons/Strip_pimec.png')}}" alt="Partners LOGO" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection