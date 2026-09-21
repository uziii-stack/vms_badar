<!DOCTYPE html>
<html lang="en">

<head>
    @include("layouts.head")
    @livewireStyles
    <!-- just remove arrow (->) to make it PWA  @->laravelPWA -->
    @laravelPWA
    <title>VMS</title>
</head>

<body>
    <div>
        <livewire:web-registration-component eventName="{{$event}}"/>
    </div>
    @include("layouts.foot")
    @livewireScripts
    @yield('scripts')
</body>