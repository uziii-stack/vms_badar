<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/png" href="{{asset('assets/images/icons/Badar-icon-128x128.png')}}" />
<link rel="stylesheet" href="{{asset('assets/css/styles.min.css')}}" />
<script src="{{asset('assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script rel="preload" src="{{asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<?php $routesIncludedTable = array('pages.organizations', 'pages.organization', 'pages.mediaGroups', 'pages.mediaGroup', 'pages.hrGroups', 'pages.hrGroup', 'pages.addDepoGuestRender', 'pages.depoGroups', 'pages.depoGroup', 'pages.mediaAllStaff', 'pages.attandee', 'pages.programs', 'pages.essentials', 'governmentOrganization.index', 'governmentStaff.index', 'temporaryPass.index'); ?>
@if(in_array(Route::currentRouteName(), $routesIncludedTable))
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.25.0/dist/bootstrap-table.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/dragtable.css">
@endif
<?php $routesIncludedSummerNote = array('templates.create', 'templates.edit', 'pages.events'); ?>
@if(in_array(Route::currentRouteName(), $routesIncludedSummerNote))
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css">
@endif
<?php $routesIncludedCharts = array('pages.dashboard', 'pages.snseaDashboard'); ?>
@if(in_array(Route::currentRouteName(), $routesIncludedCharts))
<script rel="preload" src="{{asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
<script rel="preload" src="{{asset('assets/js/dashboard.js')}}"></script>
@endif
