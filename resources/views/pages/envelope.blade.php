<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Badge Printing</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="shortcut icon" type="image/png" href="{{asset('images/icons/Badar-icon-128x128.png')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/styles.min.css')}}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,700&amp;family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500&amp;display=swap">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/badge.css') }}">
</head>

<body class="antialiased">
    <div class="container">
        <div class="row">
            <div class="btn-parent d-flex justify-content-center mt-2">
                <button class="btn btn-outline-primary" onclick="window.print()">Print this Envelope</button>
            </div>
        </div>
        @foreach ($dataNodes as $key=> $data)
        <div style="height:490px;">
            <br />
            <br />
            <br />
            <div class="row">
                <div class="col">
                </div>
                <div class="col">
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end align-items-center">
                        <p class="text-right">{{$data->depo_guest_service}} / {{$data->badge_type}}</p>
                    </div>
                </div>
                <div class="col">
                </div>
            </div>
            <br />
            <br />
            <br />
            <br />
            <div class="row">
                <div class="d-flex justify-content-evenly align-items-center envelope-class">
                    <div>
                        <h5 style="text-transform:capitalize; font-weight:700;overflow:visible;">
                            @foreach (\App\Models\Rank::where('id',$data->depo_guest_rank)->get() as $key=>$rank)
                            {{$rank->ranks_name}}
                            @endforeach
                            {{$data->depo_guest_name}}
                        </h5>
                        <h6 class="text-left" style="text-transform:uppercase; white-space: normal; word-wrap: break-word; overflow-wrap: break-word; height:40px;">
                            {{$data->depo_guest_designation}}
                        </h6>
                        <h6 class="text-left" style="text-transform:uppercase; white-space: normal; word-wrap: break-word;overflow-wrap: break-word; height:80px;">
                            {{$data->depo_address}}
                        </h6>
                    </div>
                </div>
            </div>
            <br />
            <br />
            <br />
            <br />
        </div>
        @endforeach
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"
            type="text/javascript"></script>
        <script type="text/javascript" src="{{ asset('assets/js/badge.js') }}"></script>
</body>

</html>