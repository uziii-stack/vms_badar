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
    <!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/badge.css') }}">-->
    <style>
        @page {
            size: 4in 2in;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Label */
        .tag-card {
            width: 5in;
            height: 2in;
            /* box-sizing: border-box; */
            padding: 0px 8px;
            /* border: 1px dashed #000; */
            /* Remove dashed border for real print */
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: visible;
            page-break-inside: avoid;
            white-space: pre-wrap;
            /* Stop line break as much as possible */
        }

        .tag-first-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h3,
        h4 {
            margin: 0;
            line-height: 1.5;
            font-weight: normal;
            overflow: visible;
            text-overflow: ellipsis;
            white-space: nowrap;
            /* Trim instead of breaking lines */
        }


        h3 {
            font-weight: bold;
        }

        /* h6 {
            font-size: 11px;
        }  */

        @media print {
            .tag-card {
                border: none;
            }

            .btn-outline-primary,
            .btn-outline-secondary,
            .btn-outline-info,
            .btn-contain {
                display: none;
            }

            /* Final print no border */
        }
    </style>



</head>

<body class="antialiased">
    <div class="container">
        <div class="row">
            <div class="btn-parent d-flex justify-content-center mt-2">
                <button class="btn btn-outline-primary" onclick="window.print()">Print this Tag</button>
            </div>
        </div>
        @foreach ($dataNodes as $data)
        @php
        $rank = \App\Models\Rank::find($data->depo_guest_rank);
        @endphp
        <div class="tag-card">
            <br />
            <div class="tag-first-line">
                <h5>To,</h5>
                <h6>Ref # {{ $data->badge_type }}</h6>
            </div>
            <h3>{{ $rank->ranks_name ?? '' }} {{ $data->depo_guest_name }}</h3>
            <h4>{{ $data->depo_guest_designation }}</h4>
            <h4 style="overflow: visible;
            text-overflow: ellipsis;
            white-space: pre-wrap; width:6in;">{{ $data->depo_address }}</h4>
            <h4><b>MOB # {{ $data->depo_guest_contact }}</b></h4>
            {{-- <h4>{{ $data->depo_guest_service }}</h4> --}}
        </div>

        @endforeach
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"
            type="text/javascript"></script>
        <script type="text/javascript" src="{{ asset('assets/js/badge.js') }}"></script>
</body>

</html>