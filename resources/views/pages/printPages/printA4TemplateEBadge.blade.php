<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A4 E-Badge Printing</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="icon" href="{{ $template->image_3 ? Storage::url($template->image_3) : asset('images/icons/Badar-icon-192x192.png') }}">

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            background: #f5f5f5;
            color: #111;
            font-family: Arial, sans-serif;
        }

        .btn-parent {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            padding: 12px;
        }

        .a4-template-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto 12px;
            padding: 8mm;
            background: #fff;
            overflow: hidden;
            page-break-after: always;
            break-after: page;
        }

        .a4-template-page:last-child {
            page-break-after: auto;
            break-after: auto;
        }

        .a4-template-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-template-rows: repeat(2, minmax(0, 1fr));
            gap: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border: 1px dashed #111;
        }

        .a4-section {
            min-width: 0;
            min-height: 0;
            height: 100%;
            padding: 5mm;
            overflow: hidden;
            overflow-wrap: anywhere;
        }

        .a4-section:nth-child(1),
        .a4-section:nth-child(3) {
            border-right: 1px dashed #111;
        }

        .a4-section:nth-child(1),
        .a4-section:nth-child(2) {
            border-bottom: 1px dashed #111;
        }

        .a4-section,
        .a4-section * {
            max-width: 100%;
        }

        .a4-section p,
        .a4-section ul,
        .a4-section ol,
        .a4-section h1,
        .a4-section h2,
        .a4-section h3,
        .a4-section h4,
        .a4-section h5,
        .a4-section h6 {
            margin-top: 0;
        }

        .a4-section img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .a4-template-content {
            font-size: 14px;
            line-height: 1.25;
            list-style-position: inside
        }

        .a4-template-content h1,
        .a4-template-content h2,
        .a4-template-content h3,
        .a4-template-content h4 {
            margin-bottom: 2mm;
            font-size: 16px;
            line-height: 1.1;
        }

        .a4-template-content h5,
        .a4-template-content h6 {
            margin-bottom: 1.5mm;
            font-size: 11px;
            line-height: 1.15;
        }

        .a4-template-content p {
            margin-bottom: 2mm;
        }

        .a4-template-content ul,
        .a4-template-content ol {
            margin-bottom: 2mm;
            padding-left: 4mm;
        }

        .a4-template-content li {
            margin-bottom: 1mm;
        }

        .a4-badge-logo {
            max-height: 26mm;
            object-fit: contain;
        }

        .a4-auto-title {
            margin: 0 0 8mm;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
        }

        .a4-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 5mm 10mm;
            margin-bottom: 7mm;
            font-size: 11px;
        }

        .a4-info-row {
            display: flex;
            gap: 2mm;
        }

        .a4-info-label {
            white-space: nowrap;
        }

        .a4-info-value {
            font-weight: 700;
        }

        .a4-note-list {
            margin: 4mm 0 0;
            padding-left: 4mm;
            font-size: 11px;
        }

        .a4-note-list li {
            margin-bottom: 2mm;
        }

        .a4-attendee-card {
            text-align: center;
            font-size: 11px;
        }

        .a4-event-picture {
            display: block;
            width: 100%;
            max-height: 36mm;
            margin: 0 auto 8mm;
            object-fit: contain;
        }

        .a4-attendee-name {
            margin: 0 0 3mm;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .a4-attendee-designation {
            margin: 0 0 5mm;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .a4-attendee-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 6mm 3mm;
            margin-top: 6mm;
            text-align: left;
            font-size: 11px;
        }

        .a4-attendee-meta strong {
            display: inline-block;
            margin-right: 1mm;
        }

        .a4-barcode {
            width: 48mm;
            min-height: 16mm;
            margin: 4mm auto;
            overflow: hidden;
            text-align: center;
        }

        .a4-qr-code {
            width: 34mm;
            height: 34mm;
            margin: 10mm auto;
            overflow: hidden;
        }

        @media screen and (max-width: 900px) {
            .a4-template-page {
                width: min(100vw, 210mm);
                height: calc(min(100vw, 210mm) * 1.4142857);
                padding: 10px;
            }

            .a4-section {
                padding: 10px;
                font-size: 11px;
            }
        }

        @media print {
            html,
            body {
                width: 100%;
                height: auto;
                background: #fff;
            }

            .btn-parent {
                display: none !important;
            }

            .a4-template-page {
                width: 260mm;
                height: 370mm;
                margin: 0 auto;
                padding: 0;
                overflow: hidden;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .a4-template-grid {
                width: 260mm;
                height: 370mm;
                min-height: 297mm;
            }

            .a4-section {
                page-break-inside: avoid;
                break-inside: avoid;
                font-size: 13px;
                padding-top: 5mm;
            }

            .a4-auto-title {
                font-size: 22px;
            }

            .a4-info-grid,
            .a4-note-list,
            .a4-attendee-card,
            .a4-attendee-meta {
                font-size: 13px;
            }

            .a4-attendee-name {
                font-size: 24px;
            }

            .a4-attendee-designation {
                font-size: 22px;
            }

            .a4-template-content {
                font-size: 18px;
                line-height: 1.32;
            }

            .a4-template-content h1,
            .a4-template-content h2,
            .a4-template-content h3,
            .a4-template-content h4 {
                font-size: 20px;
            }

            .a4-template-content h5,
            .a4-template-content h6 {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    @php
        $eventName = $eventModel?->display_name ?? config('localvariables.' . $event . '.eventName') ?? $event;
        $eventShortName = config('localvariables.' . $event . '.eventShortName') ?? $eventName;
        $eventDate = $eventModel ? $eventModel->start_date . ' - ' . $eventModel->end_date : config('localvariables.' . $event . '.visitDate');
        $eventLocation = $eventModel?->event_location ?? config('localvariables.' . $event . '.eventLocation') ?? '';
        $eventTime = $eventModel?->event_time ?? config('localvariables.' . $event . '.eventTime') ?? '';
        $website = $eventModel?->website ?? config('localvariables.' . $event . '.website') ?? '';
        $eventImage = $eventModel?->picture
            ? asset('storage/' . $eventModel->picture)
            : ($template->image_1 ? Storage::url($template->image_1) : asset('images/icons/' . $event . '.jpeg'));
        $eventLogo = '<img src="' . e($eventImage) . '" class="a4-badge-logo" onerror="this.style.display=\'none\'">';
        $eventPicture = '<img src="' . e($eventImage) . '" class="a4-event-picture" onerror="this.style.display=\'none\'">';
        $policyContent1 = $eventModel?->policy_content_1 ?? '';
        $policyContent2 = $eventModel?->policy_content_2 ?? '';
        $showCnicOnBadge = $eventModel?->show_cnic_on_badge ?? true;
        $showContactOnBadge = $eventModel?->show_contact_on_badge ?? true;
        $renderSection = function ($content, $attendee) use ($eventLogo, $eventPicture, $eventName, $eventShortName, $eventDate, $eventLocation, $eventTime, $website, $policyContent1, $policyContent2, $showCnicOnBadge, $showContactOnBadge) {
            $code = e($attendee->code ?? '');
            $registeredDate = isset($attendee->created_at) ? date('d-M-Y', strtotime(substr_replace($attendee->created_at, '', 10))) : '';
            $tokens = [
                '[[qr_code]]' => '<div class="a4-qr-code" data-code="' . $code . '"></div>',
                '[[bar_code]]' => '<div class="a4-barcode" data-code="' . $code . '"></div>',
                '[[event_logo]]' => $eventLogo,
                '[[event_picture]]' => $eventPicture,
                '[[event_name]]' => e($eventName),
                '[[event_short_name]]' => e($eventShortName),
                '[[event_date]]' => e($eventDate),
                '[[event_location]]' => e($eventLocation),
                '[[event_time]]' => e($eventTime),
                '[[event_website]]' => e($website),
                '[[policy_content_1]]' => $policyContent1,
                '[[policy_content_2]]' => $policyContent2,
                '[[visitor_name]]' => e($attendee->name ?? ''),
                '[[visitor_designation]]' => e($attendee->designation ?? ''),
                '[[visitor_company]]' => e($attendee->company ?? ''),
                '[[visitor_identity]]' => $showCnicOnBadge ? e($attendee->identity ?? '') : '',
                '[[visitor_nationality]]' => e($attendee?->attandeeCountry ?? $attendee?->nationality ?? ''),
                '[[visitor_sector]]' => e($attendee->sector ?? ''),
                '[[visitor_contact]]' => $showContactOnBadge ? e($attendee->contact ?? '') : '',
                '[[visitor_code]]' => $code,
                '[[registered_date]]' => e($registeredDate),
            ];

            return strtr($content ?? '', $tokens);
        };
    @endphp

    <div class="btn-parent">
        <a class="btn btn-outline-info" href="javascript:window.history.back()">Go Back</a>
        <button class="btn btn-outline-primary" onclick="window.print()">Print E-badges</button>
        <button class="btn btn-outline-secondary" onclick="downloadBadge()">Download E-badges</button>
    </div>

    @foreach($data as $attendee)
        @php
            $qrCode = '<div class="a4-qr-code" data-code="' . e($attendee->code ?? '') . '"></div>';
            $barCode = '<div class="a4-barcode" data-code="' . e($attendee->code ?? '') . '"></div>';
            $registeredDate = isset($attendee->created_at) ? date('d-M-Y', strtotime(substr_replace($attendee->created_at, '', 10))) : '';
        @endphp
        <div class="a4-template-page">
            <div class="a4-template-grid">
                <section class="a4-section">
                    <h2 class="a4-auto-title">Registration Information</h2>
                    <div class="a4-info-grid">
                        <div class="a4-info-row">
                            <span class="a4-info-label">Registered :</span>
                            <span class="a4-info-value">{{ $registeredDate }}</span>
                        </div>
                        <div class="a4-info-row">
                            <span class="a4-info-label">Registration :</span>
                            <span class="a4-info-value">Visitor</span>
                        </div>
                        <div class="a4-info-row">
                            <span class="a4-info-label">Visit Date :</span>
                            <span class="a4-info-value">{{ $eventDate }}</span>
                        </div>
                        <div class="a4-info-row">
                            <span class="a4-info-label">Timings :</span>
                            <span class="a4-info-value">{{ $eventTime }}</span>
                        </div>
                    </div>
                    {!! $qrCode !!}
                    <div class="a4-info-grid">
                        <div class="a4-info-row">
                            <span class="a4-info-label">Location :</span>
                            <span class="a4-info-value">{{ $eventLocation }}</span>
                        </div>
                        <div class="a4-info-row">
                            <span class="a4-info-label">Website :</span>
                            <span class="a4-info-value">{{ $website }}</span>
                        </div>
                    </div>
                    <ul class="a4-note-list">
                        <li><strong>This Badge is only permitted to visit the exhibition.</strong></li>
                        <li>This is a non-transferable and personal badge that must be displayed at the time of entry.</li>
                    </ul>
                </section>
                <section class="a4-section a4-attendee-card">
                    {!! $eventPicture !!}
                    <h2 class="a4-attendee-name">{{ $attendee->name ?? '' }}</h2>
                    <div class="a4-attendee-designation">{{ $attendee->designation ?? '' }}</div>
                    {!! $barCode !!}
                    <div>{{ $attendee->code ?? '' }}</div>
                    <div class="a4-attendee-meta">
                        @if($showCnicOnBadge)
                            <div><strong>CNIC/Pass :</strong>{{ $attendee->identity ?? '' }}</div>
                        @endif
                        <div><strong>Validity :</strong></div>
                        <div><strong>Nationality :</strong>{{ $attendee?->attandeeCountry ?? $attendee?->nationality ?? '' }}</div>
                        <div><strong>Sector :</strong>{{ $attendee->sector ?? '' }}</div>
                        @if($showContactOnBadge)
                            <div><strong>Mobile #</strong>{{ $attendee->contact ?? '' }}</div>
                        @endif
                    </div>
                </section>
                <section class="a4-section a4-template-content">
                    {!! $renderSection($template->head_content, $attendee) !!}
                </section>
                <section class="a4-section a4-template-content">
                    {!! $renderSection($template->body_content, $attendee) !!}
                </section>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-barcode.js') }}"></script>
    <script>
        document.querySelectorAll('.a4-barcode').forEach(function (barcode) {
            $(barcode).barcode(barcode.dataset.code, 'code128', {
                showHRI: true,
                barWidth: 2,
                barHeight: 36,
            });
        });

        document.querySelectorAll('.a4-qr-code').forEach(function (qrCode) {
            new QRCode(qrCode, {
                text: qrCode.dataset.code,
                width: 128,
                height: 128,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        function downloadBadge() {
            const pages = document.querySelectorAll('.a4-template-page');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            let chain = Promise.resolve();

            pages.forEach(function (page, index) {
                chain = chain.then(function () {
                    return html2canvas(page, {
                        scale: 2,
                        useCORS: true,
                        width: page.offsetWidth,
                        height: page.offsetHeight,
                    }).then(function (canvas) {
                        if (index > 0) {
                            pdf.addPage('a4', 'portrait');
                        }

                        const imgData = canvas.toDataURL('image/png');
                        pdf.addImage(imgData, 'PNG', 0, 0, 210, 297);
                    });
                });
            });

            chain.then(function () {
                pdf.save('a4-e-badges.pdf');
            });
        }
    </script>
</body>

</html>
