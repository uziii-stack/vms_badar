<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Printable Event E-Pass</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --green: #0b6f2a;
            --yellow: #ffd900;
            --text: #111111;
            --muted: #666666;
            --border: #d7d7d7;
        }

        html {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ececec;
            color: var(--text);
            line-height: 1.4;
        }

        .btn-parent {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            padding: 12px;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--border);
        }

        .epass-sheet {
            width: min(100%, 1500px);
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 5px;
            background: var(--green);
            border: 5px solid var(--green);
            page-break-after: always;
            break-after: page;
        }

        .epass-sheet:last-of-type {
            page-break-after: auto;
            break-after: auto;
        }

        .pdf-export-stage {
            position: fixed;
            left: -10000px;
            top: 0;
            width: 794px;
            height: 1123px;
            background: #fff;
            overflow: hidden;
            z-index: -1;
        }

        .pdf-export-stage .epass-sheet {
            width: 794px !important;
            height: 1123px !important;
            max-width: none !important;
            margin: 0 !important;
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            grid-template-rows: 1fr 1fr !important;
            gap: 5px !important;
            border: 5px solid var(--green) !important;
            padding: 0 !important;
            overflow: hidden !important;
            page-break-after: auto !important;
            break-after: auto !important;
        }

        .pdf-export-stage .panel {
            width: auto !important;
            height: auto !important;
            min-width: 0 !important;
            min-height: 0 !important;
        }

        .pdf-export-stage .info-row {
            grid-template-columns: minmax(150px, 42%) minmax(0, 1fr) !important;
        }

        .pdf-export-stage .identity-grid {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important;
        }

        .pdf-export-stage .panel-content {
            padding: 52px 34px 28px !important;
        }

        .pdf-export-stage .front-panel {
            justify-content: flex-start !important;
        }

        .pdf-export-stage .panel-label {
            padding: 6px 18px !important;
            font-size: 12px !important;
        }

        .pdf-export-stage .section-title {
            margin-bottom: 20px !important;
            font-size: 20px !important;
        }

        .pdf-export-stage .info-table {
            gap: 14px !important;
            margin-bottom: 18px !important;
        }

        .pdf-export-stage .info-row {
            gap: 14px !important;
        }

        .pdf-export-stage .info-label,
        .pdf-export-stage .info-value,
        .pdf-export-stage .detail-section h3,
        .pdf-export-stage .detail-section p {
            font-size: 11px !important;
            line-height: 1.25 !important;
        }

        .pdf-export-stage .detail-section {
            margin-top: 14px !important;
        }

        .pdf-export-stage .sponsor-area {
            margin-top: 24px !important;
        }

        .pdf-export-stage .sponsor-strip {
            max-height: 96px !important;
        }

        .pdf-export-stage .event-banner {
            width: 240px !important;
            min-height: 76px !important;
            margin-bottom: 8px !important;
        }

        .pdf-export-stage .event-title {
            margin-bottom: 4px !important;
            font-size: 20px !important;
        }

        .pdf-export-stage .event-date {
            margin-bottom: 8px !important;
            font-size: 12px !important;
        }

        .pdf-export-stage .visitor-name {
            font-size: 18px !important;
        }

        .pdf-export-stage .visitor-designation,
        .pdf-export-stage .visitor-company {
            font-size: 13px !important;
        }

        .pdf-export-stage .qr-container {
            width: 112px !important;
            margin: 8px auto !important;
        }

        .pdf-export-stage .qr-code {
            width: 112px !important;
            height: 112px !important;
            margin: 0 auto !important;
        }

        .pdf-export-stage .qr-code canvas,
        .pdf-export-stage .qr-code img {
            width: 112px !important;
            height: 112px !important;
        }

        .pdf-export-stage .identity-grid {
            gap: 8px !important;
            margin-top: 4px !important;
        }

        .pdf-export-stage .identity-label {
            font-size: 9px !important;
        }

        .pdf-export-stage .identity-value,
        .pdf-export-stage .validity {
            font-size: 10px !important;
        }

        .pdf-export-stage .visitor-type {
            margin-top: 8px !important;
            padding: 7px 14px !important;
            font-size: 20px !important;
        }

        .pdf-export-stage .rules-panel {
            padding-top: 52px !important;
        }

        .pdf-export-stage .rules-heading {
            margin-bottom: 28px !important;
            font-size: 11px !important;
        }

        .pdf-export-stage .requirement-title {
            margin-bottom: 12px !important;
            font-size: 22px !important;
        }

        .pdf-export-stage .rules-list {
            gap: 9px !important;
            padding-left: 16px !important;
        }

        .pdf-export-stage .rules-list li,
        .pdf-export-stage .guideline-text {
            font-size: 10.5px !important;
            line-height: 1.25 !important;
        }

        .pdf-export-stage .sub-heading {
            margin: 24px 0 !important;
            font-size: 11px !important;
        }

        .pdf-export-stage .thank-you,
        .pdf-export-stage .organizer-details {
            font-size: 10px !important;
            line-height: 1.25 !important;
        }

        .panel {
            position: relative;
            min-width: 0;
            min-height: 680px;
            overflow: hidden;
            background: #fff;
        }

        .panel-label {
            position: absolute;
            inset: 0 0 auto 0;
            z-index: 2;
            padding: 7px 22px;
            background: var(--green);
            color: #fff;
            font-size: clamp(13px, 1.1vw, 18px);
            font-weight: 700;
            letter-spacing: .2px;
        }

        .panel-content {
            width: 100%;
            min-height: 100%;
            padding:
                clamp(50px, 6vw, 76px) clamp(24px, 4vw, 60px) clamp(28px, 4vw, 54px);
        }

        .section-title {
            margin-bottom: 30px;
            color: var(--green);
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(20px, 2vw, 30px);
        }

        .info-table {
            display: grid;
            gap: 20px;
            margin-bottom: 28px;
        }

        .info-row {
            display: grid;
            grid-template-columns: minmax(150px, 42%) minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .info-label {
            font-size: clamp(13px, 1.2vw, 17px);
            font-weight: 700;
        }

        .info-value {
            position: relative;
            font-size: clamp(13px, 1.2vw, 17px);
            overflow-wrap: anywhere;
        }

        .info-value::before {
            content: ":";
            position: absolute;
            left: -13px;
        }

        .detail-section {
            margin-top: 23px;
        }

        .detail-section h3 {
            margin-bottom: 3px;
            color: var(--green);
            font-size: clamp(13px, 1.15vw, 17px);
        }

        .detail-section p {
            font-size: clamp(13px, 1.15vw, 17px);
            overflow-wrap: anywhere;
        }

        /* Image areas intentionally left empty.
   Add <img> tags inside these boxes whenever needed. */
        .image-slot {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 78px;
            border: 2px dashed var(--border);
            background: #fafafa;
            color: #999;
            text-align: center;
            font-size: 12px;
            overflow: hidden;
        }

        .image-slot img {
            display: block;
            width: 100%;
            height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .sponsor-area {
            width: 100%;
            margin-top: clamp(35px, 7vw, 80px);
        }

        .sponsor-strip {
            display: block;
            width: 100%;
            max-width: 720px;
            max-height: 190px;
            margin: 0 auto;
            object-fit: contain;
        }

        .front-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .event-banner {
            width: min(100%, 430px);
            min-height: 130px;
            margin: 0 auto 16px;
        }

        .event-title {
            margin-bottom: 6px;
            color: var(--green);
            font-size: clamp(24px, 2.6vw, 36px);
            line-height: 1.1;
        }

        .event-date {
            margin-bottom: 18px;
            font-size: clamp(14px, 1.4vw, 19px);
            font-weight: 700;
        }

        .visitor-name {
            font-size: clamp(22px, 2.3vw, 32px);
            font-weight: 700;
            line-height: 1.15;
        }

        .visitor-designation,
        .visitor-company {
            font-size: clamp(16px, 1.6vw, 22px);
            font-weight: 600;
            line-height: 1.2;
        }

        .qr-container {
            /* width: clamp(140px, 16vw, 200px); */
            margin: 25px auto;
        }

        .qr-slot {
            min-height: 0;
            aspect-ratio: 1 / 1;
        }

        .identity-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 20px;
            margin-top: 5px;
            justify-content: center;
            justify-items: center;
        }

        .identity-item {
            min-width: 0;
            width: 100%;
            max-width: 180px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            text-align: center;
        }

        .identity-label {
            font-size: clamp(11px, 1.05vw, 15px);
            font-weight: 700;
        }

        .identity-value {
            font-size: clamp(12px, 1.05vw, 15px);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .validity {
            margin-top: 27px;
            font-size: clamp(12px, 1.05vw, 15px);
        }

        .visitor-type {
            width: 100%;
            margin-top: 30px;
            padding: 10px 20px;
            border-radius: 4px;
            /* background: var(--yellow); */
            color: #eee;
            font-size: clamp(23px, 2.8vw, 36px);
            font-weight: 700;
            line-height: 1;
        }

        .rules-panel {
            padding-top: clamp(40px, 5vw, 60px);
        }

        .rules-heading {
            margin-bottom: 55px;
            font-size: clamp(14px, 1.2vw, 18px);
        }

        .requirement-title {
            margin-bottom: 20px;
            font-size: clamp(25px, 2.4vw, 34px);
        }

        .rules-list {
            display: grid;
            gap: 15px;
            padding-left: 20px;
            list-style-position: outside;
        }

        .rules-list li {
            font-size: clamp(13px, 1.15vw, 17px);
            font-weight: 600;
        }

        .sub-heading {
            margin: 45px 0;
            font-size: clamp(14px, 1.2vw, 18px);
        }

        .guideline-text {
            margin-bottom: 40px;
            font-size: clamp(14px, 1.25vw, 18px);
            font-weight: 700;
        }

        .thank-you {
            margin-bottom: 35px;
            font-size: clamp(12px, 1vw, 15px);
            font-style: italic;
        }

        .organizer-details {
            font-size: clamp(12px, 1.05vw, 16px);
        }

        .organizer-details p {
            margin-top: 8px;
        }

        .organizer-details span {
            text-decoration: underline;
        }

        .bottom-logo {
            display: flex;
            justify-content: flex-end;
            margin-top: 35px;
        }

        .organizer-logo {
            width: 160px;
            min-height: 75px;
        }

        /* Tablet / medium screens */
        @media (max-width: 900px) {
            .epass-sheet {
                grid-template-columns: 1fr;
                max-width: 700px;
            }

            .panel {
                min-height: auto;
            }

            .panel-content {
                padding: 65px clamp(24px, 7vw, 50px) 40px;
            }
        }

        /* Mobile */
        @media (max-width: 550px) {
            body {
                background: #fff;
            }

            .epass-sheet {
                border-width: 3px;
                gap: 3px;
            }

            .panel-label {
                padding: 6px 15px;
            }

            .panel-content {
                padding: 55px 20px 30px;
            }

            .info-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .info-value::before {
                display: none;
            }

            .identity-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .identity-item {
                max-width: none;
            }

            .sponsor-area {
                margin-top: 30px;
            }

            .sponsor-strip {
                max-width: 100%;
                max-height: 1400px;
            }

            .rules-heading {
                margin-bottom: 35px;
            }

            .sub-heading {
                margin: 35px 0 30px;
            }
        }

        /* ==========================================================
   PRINT / PDF
   Always keep all four sections on ONE A4 page in a 2 × 2 grid.
   Screen/mobile responsiveness does NOT affect print layout.
========================================================== */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            html,
            body {
                width: 100% !important;
                min-height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                overflow: visible !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .btn-parent {
                display: none !important;
            }

            .epass-sheet {
                position: relative !important;
                width: 100vw !important;
                height: 100vh !important;
                max-width: none !important;
                margin: 0 !important;

                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                grid-template-rows: 1fr 1fr !important;

                gap: 1.2mm !important;
                padding: 1.2mm !important;
                border: 0 !important;
                background: var(--green) !important;

                overflow: hidden !important;
                page-break-after: always !important;
                break-after: page !important;
            }

            .panel {
                width: auto !important;
                height: auto !important;
                min-width: 0 !important;
                min-height: 0 !important;

                margin: 0 !important;
                border: 0 !important;

                overflow: hidden !important;

                break-inside: avoid !important;
                page-break-inside: avoid !important;
                break-after: auto !important;
                page-break-after: auto !important;
            }

            .panel-content {
                padding: 10mm 8mm 6mm !important;
                height: 100% !important;
                min-height: 0 !important;
                overflow: hidden !important;
            }

            .epass-sheet:last-of-type {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            .panel-label {
                padding: 1.8mm 4mm !important;
                font-size: 10pt !important;
                background: var(--green) !important;
                color: #fff !important;
            }

            .section-title {
                margin-bottom: 7mm !important;
                font-size: 18pt !important;
            }

            .info-table {
                gap: 4.5mm !important;
                margin-bottom: 6mm !important;
            }

            .info-row {
                grid-template-columns: 42% 58% !important;
                gap: 3mm !important;
            }

            .info-label,
            .info-value,
            .detail-section h3,
            .detail-section p {
                font-size: 12pt !important;
                line-height: 1.35 !important;
            }

            .detail-section {
                margin-top: 5mm !important;
            }

            .panel:first-child .panel-content,
            .front-panel {
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
            }

            .sponsor-area {
                margin-top: 10mm !important;
                width: 100% !important;
            }

            .sponsor-strip {
                width: 100% !important;
                max-width: 88mm !important;
                max-height: 34mm !important;
                object-fit: contain !important;
            }

            .image-slot {
                min-height: 24mm !important;
                border-width: 0.35mm !important;
            }

            .event-banner {
                width: 82mm !important;
                min-height: 32mm !important;
                margin-bottom: 4mm !important;
            }

            .event-title {
                margin-bottom: 2mm !important;
                font-size: 21pt !important;
            }

            .event-date {
                margin-bottom: 3mm !important;
                font-size: 11pt !important;
            }

            .visitor-name {
                font-size: 18pt !important;
            }

            .visitor-designation,
            .visitor-company {
                font-size: 13pt !important;
            }

            .qr-container {
                width: 42mm !important;
                margin: 5mm auto !important;
            }

            .identity-grid {
                grid-template-columns: repeat(auto-fit, minmax(24mm, 1fr)) !important;
                gap: 2mm !important;
                margin-top: 1mm !important;
                justify-items: center !important;
            }

            .identity-item {
                max-width: 34mm !important;
            }

            .identity-label {
                font-size: 8.5pt !important;
            }

            .identity-value {
                font-size: 9pt !important;
            }

            .validity {
                margin-top: 4mm !important;
                font-size: 9pt !important;
            }

            .visitor-type {
                margin-top: 4mm !important;
                padding: 2.5mm 4mm !important;
                font-size: 20pt !important;
                color: #fff !important;
            }

            .visitor-type.is-yellow {
                color: #000 !important;
            }

            .rules-panel {
                padding-top: 11mm !important;
            }

            .rules-heading {
                margin-bottom: 9mm !important;
                font-size: 10.5pt !important;
            }

            .requirement-title {
                margin-bottom: 4mm !important;
                font-size: 19pt !important;
            }

            .rules-list {
                gap: 3mm !important;
                padding-left: 5mm !important;
            }

            .rules-list li {
                font-size: 10pt !important;
                line-height: 1.32 !important;
            }

            .sub-heading {
                margin: 8mm 0 !important;
                font-size: 11pt !important;
            }

            .guideline-text {
                margin-bottom: 7mm !important;
                font-size: 10.5pt !important;
                line-height: 1.32 !important;
            }

            .thank-you {
                margin-bottom: 6mm !important;
                font-size: 9.5pt !important;
                line-height: 1.3 !important;
            }

            .organizer-details {
                font-size: 9.5pt !important;
            }

            .organizer-details p {
                margin-top: 1.5mm !important;
            }

            .bottom-logo {
                margin-top: 5mm !important;
            }

            .organizer-logo {
                width: 28mm !important;
                min-height: 14mm !important;
            }

            /* Empty placeholders remain invisible when printing.
     Once an <img> is placed inside a slot, the image will print normally. */
            .image-slot:empty {
                border-color: transparent !important;
                background: transparent !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/qr.css') }}">
</head>

<body>
    <div class="btn-parent">
        <a class="btn btn-outline-info" href="javascript:window.history.back()">Go Back</a>
        <button class="btn btn-outline-primary" onclick="window.print()">Print this E-badge</button>
        <button class="btn btn-outline-secondary" onclick="downloadBadge()">Download this E-badge</button>
    </div>

    @php
        $badgeItems = $data instanceof \Illuminate\Support\Collection ? $data : collect([$data]);
        $policyContent1 = trim($event->policy_content_1 ?? '');
        $policyContent2 = trim($event->policy_content_2 ?? '');
        $showCnicOnBadge = $event->show_cnic_on_badge ?? true;
        $showContactOnBadge = $event->show_contact_on_badge ?? true;
    @endphp

    @foreach($badgeItems as $data)
    <main class="epass-sheet">

        <!-- BACK SIDE -->
        <section class="panel">
            <div class="panel-label">BACK SIDE</div>

            <div class="panel-content">
                <h2 class="section-title">REGISTRATION INFORMATION</h2>

                <div class="info-table">
                    <div class="info-row">
                        <div class="info-label">REGISTRATION DATE</div>
                        <div class="info-value">03 Jul 2026</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">REGISTRATION REF</div>
                        <div class="info-value">{{$data->code}}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">EVENT DATE</div>
                        <div class="info-value">{{$event->start_date}} - {{$event->end_date}}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">TIMINGS</div>
                        <div class="info-value">{{$event->event_time}}</div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>VENUE ADDRESS</h3>
                    <p>{{$event->event_location}}</p>
                </div>

                <div class="detail-section">
                    <h3>ORGANIZER ADDRESS</h3>
                    <p>
                        C 175 Ansari Saab Road, Near Aziz Bhatti Park,
                        Block 9 Gulshan-e-Iqbal, Karachi
                    </p>
                </div>

                <div class="detail-section">
                    <h3>WEBSITE/URL</h3>
                    <p>{{$event->website}}</p>
                </div>

                <!-- Put sponsor/logo images inside these empty slots later -->
                @if($event->sponsor_picture)
                    <div class="sponsor-area">
                        <img class="sponsor-strip" src="{{asset('storage/' . $event->sponsor_picture)}}" alt="Sponsors" onerror="this.style.display='none'">
                    </div>
                @endif
            </div>
        </section>

        <!-- FRONT SIDE -->
        <section class="panel">
            <div class="panel-label">FRONT SIDE</div>

            <div class="panel-content front-panel">

                <!-- Put the event banner image here later -->
                <div class="image-slot event-banner">
                    <img src="{{asset('storage/' . $event->picture)}}" onerror="this.style.display='none'">
                </div>

                <h1 class="event-title">{{$event->display_name}}</h1>
                <div class="event-date">{{$event->start_date}} - {{$event->end_date}}</div>

                <div class="visitor-name">{{$data->name}}</div>
                <div class="visitor-designation">{{$data->designation}}</div>
                <div class="visitor-company">{{$data->company}}</div>

                <div class="qr-container">
                    <!-- Put QR image here later -->
                    <div class="qr-code" data-code="{{ $data->code ?? '' }}"></div>
                </div>

                <div class="identity-grid">
                    <div class="identity-item">
                        <span class="identity-label">NATIONALITY</span>
                        <span class="identity-value">{{$data->nationality}}</span>
                    </div>

                    @if($showCnicOnBadge)
                        <div class="identity-item">
                            <span class="identity-label">CNIC/PASSPORT</span>
                            <span class="identity-value">{{$data->identity}}</span>
                        </div>
                    @endif

                    @if($showContactOnBadge)
                        <div class="identity-item">
                            <span class="identity-label">MOBILE NUMBER</span>
                            <span class="identity-value">{{$data->contact}}</span>
                        </div>
                    @endif
                </div>

                <div class="validity">
                    ACCESS VALIDITY:
                    <strong>{{$event->start_date}} - {{$event->end_date}}</strong>
                </div>

                <div class="visitor-type" style="background-color: {{ $type[0] }}; color: {{ $type[0] === 'yellow' ? '#000' : '#fff' }} !important;">{{$type[1]}}</div>

            </div>
        </section>

        <!-- GENERAL RULES -->
        <section class="panel">
            <div class="panel-content rules-panel">


                @if($policyContent1)
                    {!! $policyContent1 !!}
                @else
                    <h2 class="requirement-title">E-Pass Requirement:</h2>

                    <ul class="rules-list">
                        <li>Print your E-Pass on A4 size paper and bring it to the venue.</li>
                        <li>Entry is strictly with a valid event E-Pass only.</li>
                        <li>
                            You may be denied entry or removed from the venue if you do not
                            have a valid E-Badge or event pass.
                        </li>
                        <li>
                            It is mandatory to have Original ID card
                            (or passport for international visitors).
                        </li>
                        <li>Weapons, alcohol &amp; flammable items are strictly prohibited.</li>
                        <li>Dress: Formal and smart casual attire is mandatory.</li>
                        <li>Duplicate E-Pass or Passes are not acceptable.</li>
                    </ul>
                @endif

            </div>
        </section>

        <!-- HEALTH AND SAFETY -->
        <section class="panel">
            <div class="panel-content rules-panel">


                @if($policyContent2)
                    {!! $policyContent2 !!}
                @else
                    <ul class="rules-list">
                        <li>First aid and medical assistance are available on-site.</li>
                        <li>Report any health issues immediately.</li>
                        <li>Emergency exits are clearly marked for your convenience.</li>
                    </ul>

                    <h3 class="sub-heading">ADDITIONAL GUIDELINES</h3>

                    <p class="guideline-text">
                        The organizers reserve the right to conduct security checks
                        and deny entry or remove anyone violating the rules.
                    </p>

                    <p class="thank-you">
                        Thank you for your cooperation. We look forward to providing
                        you with a valuable and enjoyable event experience!
                    </p>
                @endif

            </div>
        </section>

    </main>
    @endforeach
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"
        type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-barcode.js') }}"></script>
    <script>
        function renderQrCodes(root, size = 128) {
            root.querySelectorAll('.qr-code').forEach(function (qrCode) {
                qrCode.innerHTML = '';
                new QRCode(qrCode, {
                    text: qrCode.dataset.code || '',
                    width: size,
                    height: size,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            });
        }

        renderQrCodes(document);

        function downloadBadge() {
            const pages = document.querySelectorAll('.epass-sheet');
            const toolbar = document.querySelector('.btn-parent');

            if (!pages.length) {
                return;
            }

            if (toolbar) {
                toolbar.style.display = 'none';
            }

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            let chain = Promise.resolve();

            pages.forEach(function(page, index) {
                chain = chain.then(function() {
                    const stage = document.createElement('div');
                    stage.className = 'pdf-export-stage';
                    const clone = page.cloneNode(true);
                    stage.appendChild(clone);
                    document.body.appendChild(stage);
                    renderQrCodes(clone, 112);

                    return html2canvas(clone, {
                        scale: 2,
                        useCORS: true,
                        backgroundColor: '#ffffff',
                        width: 794,
                        height: 1123,
                        windowWidth: 794,
                        windowHeight: 1123,
                    }).then(function(canvas) {
                        if (index > 0) {
                            pdf.addPage('a4', 'portrait');
                        }

                        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 210, 297);
                    }).finally(function() {
                        stage.remove();
                    });
                });
            });

            chain.then(function() {
                pdf.save('e-badges.pdf');
            }).finally(function () {
                if (toolbar) {
                    toolbar.style.display = '';
                }
            });
        }
    </script>
</body>

</html>
