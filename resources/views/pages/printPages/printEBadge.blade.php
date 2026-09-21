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
    <link rel="icon" href="{{asset('images/icons/Badar-icon-192x192.png')}}">

    <style>
        @media (max-width: 768px) {
            .container-first-child {
                height: 1800px;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="btn-parent">
        <a class="btn btn-outline-info" href="javascript:window.history.back()">Go Back</a>
        <button class="btn btn-outline-primary" onclick="window.print()">Print this E-badge</button>
        <button class="btn btn-outline-secondary" onclick="downloadBadge()">Download this E-badge</button>
    </div>
    <div class="container mt-3">
        <div class="row container-first-child">
            <div class="col-md-6 parent-print-program d-print-inline badge-box">
                <div>
                    <h3 style='text-transform: uppercase; text-align:center;'>Registration Information</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                Registered : <b>
                                    <?php echo date("d-M-Y", strtotime(substr_replace($data->created_at, "", 10))); ?>
                                </b>
                            </p>
                            <p>
                                Visit Date : <b>
                                    <!-- {{isset($data->nationality) && $data->nationality !=
                                    'Pakistan'?config('localvariables.visitDate'):'09-10th February 2026'}} -->
                                    {{config('localvariables.'.$event.'.visitDate')}}
                                </b>
                            </p>
                        </div>
                        <div class="col-md-6">

                            <p>
                                Registration : <b>Visitor</b>
                            </p>
                            <p>
                                Timings : <b>
                                    {{config('localvariables.'.$event.'.eventTime')}}
                                </b>
                            </p>
                        </div>
                    </div>



                    <!-- QR Code Tag -->
                    <div class="qr-code" id="qrCode"></div>
                    <h2 style="text-align:center;">
                        {{config('localvariables.' . $event . '.eventShortName')}}
                    </h2>
                    <br />
                    <br />
                    <!-- <p>
                        Venue :
                        {{config('localvariables.'.$event.'.eventLocation')}}
                    </p> -->
                    <p>
                        Location :
                        {{config('localvariables.'.$event.'.eventLocation')}}
                    </p>
                    <p>
                        Website :
                        <span style="text-transform:lowercase">
                            {{config('localvariables.'.$event.'.website')}}
                        </span>
                    </p>
                    <p>
                    <ul class="badge-point list-rules">
                        <li>
                            <b>This Badge is only permitted to visit the exhibition.</b>
                        </li>
                        <li>
                            This is a non-transferable and personal badge that must be displayed at the time of entry.
                        </li>
                    </ul>
                    </p>
                    <br />
                    <br />
                    <br />
                </div>
            </div>
            <div class="col-md-6 parent-print-program text-center d-print-inline badge-box-2 px-3">
                <div>
                    <div class="card-border">
                        <div class="logo-child">
                            <div>
                                <img src="{{asset('images/icons/'.$event.'.jpeg')}}" style="width:100%;height:auto;"
                                    class="logo-img responsive" alt="{{config('localvariables.'.$event.'.eventName')}}" />&nbsp;&nbsp;
                            </div>
                            <div>
                                <h5 style="text-transform: uppercase; text-align:center; font-weight:700">
                                    {{config('localvariables.'.$event.'.eventShortName')}}
                                </h5>
                            </div>
                        </div>
                        <h4 style="text-transform:uppercase;">
                            <?php echo $data->name ?? ''; ?>
                        </h4>
                        <!-- <br /> -->
                        <h5 style="text-transform:uppercase;">
                            <?php echo $data->designation ?? ''; ?>
                        </h5>
                        <!-- <br /> -->
                        <h5 style="text-transform:uppercase;">
                            <?php echo $data->company ?? ''; ?>
                        </h5>
                        <!-- Bar Code Tag -->
                        <div id="barCode" custom-id="<?php echo $data->code; ?>"></div>
                        <div class="row" style="margin-bottom:0px;margin-top:5px;">
                            <div class="col-md-6 my-3"><b>CNIC/Pass : {{$data->identity ?? ''}}</b></div>
                            <div class="col-md-6 my-3"><b>Validity : {{config('localvariables.'.$event.'.visitDate')}}</b></div>
                            <div class="col-md-4 my-3"><b>Nationality : {{$data?->attandeeCountry??$data?->nationality }}</b></div>
                            <div class="col-md-4 my-3"><b>Sector : {{$data->sector ?? ''}}</b></div>
                            <div class="col-md-4 my-3"><b>Mobile # {{$data->contact ?? ''}}</b></div>
                        </div>
                        <div class="logo-child" style="margin-bottom:0px;margin-top:0px;">
                            <!-- <h2>Visitor</h2> -->
                            {{-- <img src="{{asset('/images/icons/pimec-2025-logostrip.png')}}" style="height: 100px;"
                            class="img-fluid" alt="BXSS" /> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Commented Code # 6 -->
        <div class="row container-second-child">
            <div class="col-md-6 parent-print-program d-print-inline badge-box">
                <div>
                    <h4 style="text-transform: uppercase;">GENERAL RULES FOR EVENT VISITORS</h4>
                    <p>
                        Welcome to {{ config('localvariables.' . $event . '.eventName') }}! To ensure a safe and productive
                        experience for all attendees, please adhere to the following rules:
                    </p>
                    <p><b>REGISTRATION AND ENTRY</b></p>
                    <ul class="list-rules">
                        <li><b>Badge Issuance: </b>Upon check-in, and presenting the e-Badge, you will
                            receive an event badge which must be worn at all times.</li>
                        <li><b>Badge Replacement: </b>If you lose your badge, visit the registration desk
                            for replacement.</li>
                        <li><b>Non-transferable: </b> Event badges are non-transferable. Each badge is
                            unique to the registered attendee and must not be shared.</li>
                        <li><b>Restricted Items: </b> Weapons, hazardous materials, Camera/DSLR,
                            mobile phone and any items prohibited by venue regulations are not
                            allowed.</li>
                        <li><b>Bag Check: </b> All bags are subject to security checks upon entry.</li>
                        <li><b>Dress Code: </b>Please note that the dress code for the event is National/
                            Business Attire.</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 parent-print-program d-print-inline badge-box-2">
                <div>
                    <h4 style="text-transform: uppercase;">Health and Safety</h4>
                    <ul class="list-rules">
                        <li><b>Symptom Monitoring : </b>Monitor your health and do not
                            attend the event if you are experiencing any symptoms of
                            illness or have been exposed to someone with COVID-19.
                        </li>
                        <li><b>Incident Reporting : </b> Report any health and safety concerns
                            or incidents to event staff immediately for prompt action.
                            Emergency Procedures: Be aware of emergency exits and
                            procedures. Follow all emergency instructions from venue
                            staff.</li>
                    </ul>
                    <h4 style="text-transform: uppercase;">ADDITIONAL GUIDELINES
                    </h4>
                    <ul class="list-rules">
                        <li><b>Accessibility : </b> If you have any accessibility needs, contact
                            the event organizers in advance for facilitation.
                        </li>
                        <li><b>Event App :</b>Use the event app (if available) for updates,
                            schedules, and navigation assistance.</li>
                    </ul>
                    <b class="my-0">
                        Thank you for your cooperation. We look forward to providing you with a
                        valuable and informative event experience!
                    </b>
                    <div class="row my-0">
                        <!-- <div class="col-md-12 my-0">
                            <br />
                            <h4 class="my-1" class="text-capitalize"><i>Pakistan Software Export Board</i></h4>
                            <p class="my-1">6th Floor, State Life Tower, Jinnah Ave,</p>
                            <p class="my-1">Block L F 7/4 Blue Area, Islamabad.</p>
                            <p class="my-1">Tel : 0800 01010</p>
                            <p class="my-1">Email : <a href="info@pseb.org.pk" target="_blank">info@pseb.org.pk</a>
                            </p>
                            <p class="my-1">URL : <a href="www.techdestination.com" target="_blank">www.techdestination.com</a></p>
                        </div> -->
                        <!-- <div class="col-md-4 my-0">
                            <img src="{{asset('/images/badarexpo_qr_code.png')}}" style="height: 150px;width: 200px;"
                                class="img-fluid" alt="www.badarexpo.com" />
                        </div> -->
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