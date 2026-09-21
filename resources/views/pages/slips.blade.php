<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Slip Printing</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slip.css') }}">
    <style>

    </style>
</head>

<body>
    <div>
        @foreach ($data as $key=> $node)
        <x-slips :componentKey="$key" :slipData="$node" />
        @endforeach
    </div>

    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-barcode.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/slips.js') }}"></script>
</body>

</html>