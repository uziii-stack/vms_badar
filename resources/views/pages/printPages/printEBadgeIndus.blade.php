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
    <link rel="icon" href="{{asset('images/icons/Indus-ai.png')}}">

</head>

<body class="antialiased">
    <div class="btn-parent">
        <button class="btn btn-outline-primary" onclick="window.print()">Print this E-badge</button>
        <button class="btn btn-outline-secondary" onclick="downloadBadge()">Download this E-badge</button>
        <!-- <a class="btn btn-outline-info" href="https://register.ideaspakistan.gov.pk/">Go Back To Form</a> -->
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6 parent-print-program-1 d-print-inline badge-box">
                <div>
                    <br />
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <p>
                                <b> Registered :
                                    <?php echo date("d-M-Y", strtotime(substr_replace($data->created_at, "", 10))); ?>
                                </b>
                            </p>
                            <p>
                                <b> Visit Date : 09-10 Feb 26</b>
                            </p>
                            <p>
                                <b> Registration : Visitor</b>
                            </p>
                            <p>
                                <b> Nationality : {{$data?->attandeeCountry??$data?->nationality }}</b>
                            </p>
                            <p>
                                <b> Timings :
                                    <?php echo config('localvariables.eventTime'); ?>
                                </b>
                            </p>
                            <p><b>Mobile : {{$data->contact ?? ''}}</b></p> 
                        </div>-->
                        <div class="col-md-12">
                            <h4 style="text-transform:uppercase; text-align:left;">
                                DESIGNATION : <?php echo $data->designation ?? ''; ?>
                            </h4>
                            <br />
                            <h4 style="text-transform:uppercase; text-align:left;">
                                ORGANIZATION : <?php echo $data->company ?? ''; ?>
                            </h4>
                            <br />
                            <div class="qr-code" id="qrCode"></div>
                            <!-- <h4 style="text-align:center;">
                                <?php echo config('localvariables.eventName'); ?>
                            </h4> -->
                        </div>
                    </div>
                    <!-- QR Code Tag -->
                </div>
            </div>
            <div class="col-md-6 parent-print-program-1 text-center d-print-inline badge-box">
                <div>
                    <div class="card-border">
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <div style="margin:0 auto; width:308px;">
                                    <h4 style="text-transform:uppercase;">
                                        <?php echo $data->name ?? ''; ?>
                                    </h4>
                                    <!-- <br /> -->
                                    <!-- <h6 style="text-transform:uppercase;">
                                        <?php echo $data->designation ?? ''; ?>
                                    </h6>
                                    <br />
                                    <h6 style="text-transform:uppercase;">
                                        <?php echo $data->company ?? ''; ?>
                                    </h6>
                                    <h6>Identity : {{$data->identity ?? ''}}</h6> -->

                                    <!-- Bar Code Tag -->
                                    <div id="barCode" custom-id="<?php echo $data->code; ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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