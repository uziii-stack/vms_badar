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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,700&amp;family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500&amp;display=swap">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/e-badge.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/qr.css') }}">
    <link rel="icon" href="{{Storage::url($template[0]->image_3)}}" type="image/x-icon">
    <style>
        /* Default screen display */
        .print-header {
            position: static;
            width: 100%;
            /* padding: 10px 0; */
            background: white;
            text-align: left;
        }

        /* Logo alignment for UI */
        .print-header img {
            width: 120px;
            height: 80px;
            object-fit: contain;
            margin: 0 10px;
        }

        @media print {

            .print-header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                text-align: left;
                z-index: 999;
                background: white;
            }

            .print-header img {
                width: 120px;
                height: 80px;
                object-fit: contain;
                margin: 0 10px;
            }

            body {
                margin-top: 50px !important;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }

        }

        .letter-page-break {
            page-break-after: always;
        }
    </style>


</head>

<body class="antialiased">
    <div class="btn-parent">
        <button class="btn btn-outline-primary" onclick="window.print()">Print this {{$template[0]->type}}</button>
        <button class="btn btn-outline-secondary" onclick="downloadBadge()">Download this
            {{$template[0]->type}}</button>
        <button class="btn btn-outline-info" onclick="history.back()">Go Back To Form</button>
    </div>
    <div class="print-header">
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <img src="{{Storage::url($template[0]->image_1)}}" onerror="this.style.display='none'">
                <img src="{{Storage::url($template[0]->image_2)}}" onerror="this.style.display='none'">
            </div>
            <div class="p-2 bd-highlight">
                <img src="{{Storage::url($template[0]->image_3)}}" style="height:120px;"
                    onerror="this.style.display='none'">
            </div>
        </div>
    </div>

    @foreach($data as $dataNode)
    <div class="container-fluid letter-page-break">
        <!-- <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <img onerror="this.style.display='none'" style="object-fit: fit; align:right; padding:0px 10px;"
                    width="120" height="80" src="{{Storage::url($template[0]->image_1)}}" />

                <img onerror="this.style.display='none'" style="object-fit: fit; align:right; padding:0px 10px;"
                    width="120" height="80" src="{{Storage::url($template[0]->image_2)}}" />
            </div>
            <div class="p-2 bd-highlight"><img onerror="this.style.display='none'" style="object-fit: fit; align:right;"
                    width="120" height="120" src="{{Storage::url($template[0]->image_3)}}" /></div>
        </div> -->
        {!!$template[0]->head_content!!}
        <div class="letter-first-line d-flex justify-content-between">
            <div>
                <b>To :&nbsp;&nbsp;{{$dataNode->depo_guest_name}},</b>
            </div>
            <div>
                <h6>Ref # {{$dataNode->badge_type}}</h6>
            </div>
        </div>
        <br />
        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$dataNode->depo_guest_designation}}</span>
        {!!$template[0]->body_content!!}
        {!!$template[0]->foot_content!!}
    </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"
        type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-barcode.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/badge.js') }}"></script>
</body>

</html>