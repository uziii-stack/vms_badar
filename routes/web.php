<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\{
    DashboardController,
    TemplateController,
    SignInController,
    ActivationRequest,
    LogoutController,
    ActivateProfileController,
    AttandeeController,
    BadgeConotroller,
    InviteesController,
    CouponsController,
    DepoGroupController,
    EssentialsController,
    HRController,
    MediaController,
    OrganizationController,
    ProfileImageController,
    ProgramController,
    UserPanelController,
    SignUpController,
    SlipController,
    SummaryPanelController,
    UpdateProfileController,
    UserFullProfileController,
    SnSeaController,
    GovernmentStaffController,
    GovernmentOrganizationController,
    TemporaryPassController,
    DataScanController,
    AccountCreationController,
    AttendanceController,
};



// use Illuminate\Support\Facades\Mail;


Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});


Route::get('/newRegistration/{event}', function ($event) {
    $selectedEvent = Event::with('sessions')
        ->where('name', $event)
        ->where('deleted', 0)
        ->first();

    return view('pages.selfRegistration', [
        'event' => $event,
        'eventSessions' => $selectedEvent?->sessions ?? collect(),
    ]);
})->middleware('allowIframe');

Route::get('/webRegistration/{event}', function ($event) {
    return view('pages.webRegistration', ['event' => $event]);
});

Route::get('/guestRegistration/{event}', function ($event) {
    return view('pages.guestRegistration', ['event' => $event]);
});

Route::get('/slip/{slipId}', [SlipController::class, 'render'])->name('pages.slips');
Route::get('/temporarySlip/{slipId}', [SlipController::class, 'temporarySlipRender'])->name('pages.temporarySlip');

Route::get('/login', function () {
    if (auth()?->user()?->uid) {
        return redirect()->route('pages.dashboard');
    } else {
        return view('pages.signIn');
    }
})->name("login");

Route::get('/accountActivation', function () {
    if (auth()?->user()?->uid) {
        return redirect()->route('pages.dashboard');
    } else {
        return view('pages.activation');
    }
})->name("accountActivation");

Route::post('/signInRequest', [SignInController::class, 'signIn'])->name('request.signIn');
Route::post('/signUpRequest', [SignUpController::class, 'signUp'])->name('request.signUp');
Route::post('/activationRequest', [ActivationRequest::class, 'activation'])->name('request.activation');

Route::get('/badge/{ids}', [BadgeConotroller::class, 'printEBadge'])->name('pages.Ebadge');
Route::get('/a4EBadge/{event}/{type}/{ids}', [BadgeConotroller::class, 'printA4EBadge'])->name('pages.Ebadge');
Route::get('/indusBadge/{ids}', [BadgeConotroller::class, 'printIndusBadge'])->name('pages.IndusEbadge');
Route::get('/eventBadge/{event}/{ids}', [BadgeConotroller::class, 'printEventBadge'])->name('pages.EventEbadge');

Route::group(['middleware' => 'auth'], function () {

    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'renderView'])->name("pages.dashboard");


    Route::get('/profileUser/{id}', [UserFullProfileController::class, 'render'])->name('pages.profileUser');
    // Route::get('/userProfile/profileActivation', [ActivateProfileController::class, 'renderProfileActivation'])->name('pages.dashboard');
    Route::get('/userProfile/myProfile', [UserFullProfileController::class, 'renderMyProfile'])->name('pages.myProfile');
    Route::post('/imageUpload', [ProfileImageController::class, 'imageBlobUpload'])->name('request.imageUpload');
    Route::post('/updateProfile', [UpdateProfileController::class, 'updateProflie'])->name('request.updateProfile');
    Route::post('/updateProfilePassowrd', [UpdateProfileController::class, 'updatePassword'])->name('request.updatePassword');
    Route::post('/activateProfile', [ActivateProfileController::class, 'activateProfile'])->name('request.activateProfile');

    Route::get('/badge/{type}/{ids}/{flag?}', [BadgeConotroller::class, 'render'])->name('pages.badge');
    Route::post('/send-mail/{uid}/{pass?}', [AccountCreationController::class, 'html_email'])->name('request.sendMailAgain');
    Route::get('/printEnvelope/{type}/{ids}', [BadgeConotroller::class, 'printDelegationEnvelope'])->name("pages.printDelegationEnvelope");
    Route::get('/printTag/{type}/{ids}', [BadgeConotroller::class, 'printDelegationTag'])->name("pages.printDelegationTag");
    Route::get('/printWithTemplate/{type}/{ids}', [BadgeConotroller::class, 'printWithTemplate'])->name("pages.printWithTemplate");

    // Logout Routes
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout.request');

    Route::middleware('adminCheck')->group(function () {

        // User Panel Routes
        Route::get('/userPanel', [UserPanelController::class, 'render'])->name("pages.userPanel");

        // Summary Panel Routes
        Route::get('/summary', [SummaryPanelController::class, 'render'])->name("pages.summaryPanel");



        // My profile
        Route::post('/updateAuthority', [UpdateProfileController::class, 'updateAuthority'])->name('request.updateAuthority');

        // Organizations
        Route::get('/organizations', [OrganizationController::class, 'render'])->name('pages.organizations');
        Route::get('/getOrganizations', [OrganizationController::class, 'getOrganizations'])->name('request.getOrganizations');
        Route::post('/addOrganizationRequest', [OrganizationController::class, 'addOrganization'])->name('request.addOrganization');
        Route::get('/addOrganization/{id?}', [OrganizationController::class, 'addOrganizationRender'])->name('pages.addOrganization');
        Route::get('/getOrganizationStats', [OrganizationController::class, 'getStats'])->name('request.getOrganizationStats');
        Route::post('/updateOrganizationRequest/{id}', [OrganizationController::class, 'updateOrganization'])->name('request.updateOrganization');

        // Hr
        Route::get('/hrGroups', [HRController::class, 'render'])->name('pages.hrGroups');
        Route::get('/getHrGroups', [HRController::class, 'getHrGroups'])->name('request.getHrGroups');
        Route::post('/addHrGroupsRequest', [HRController::class, 'addHrGroup'])->name('request.addHrGroups');
        Route::get('/addHrGroups/{id?}', [HRController::class, 'addHrGroupRender'])->name('pages.addHrGroups');
        Route::get('/getHrGroupsStats', [HRController::class, 'getStats'])->name('request.getHrGroupsStats');
        Route::post('/updateHrGroupsRequest/{id}', [HRController::class, 'updateHrGroup'])->name('request.updateHrGroups');
    });

    Route::middleware('mediaUserCheck')->group(function () {
        // Media
        Route::get('/mediaGroups', [MediaController::class, 'render'])->name('pages.mediaGroups');
        Route::get('/getMedia', [MediaController::class, 'getMedia'])->name('request.getMedia');
        Route::get('/addMedia/{id?}', [MediaController::class, 'addMedia'])->name('pages.addMedia');
        Route::get('/getMediaStats', [MediaController::class, 'getStats'])->name('request.getMediaStats');
        Route::post('/addMediaRequest', [MediaController::class, 'addMediaRequest'])->name('request.addMediaRequest');
        Route::post('/updateMediaRequest/{id}', [MediaController::class, 'updateMedia'])->name('request.updateMediaRequest');
        Route::get('/mediaAllStaff', [MediaController::class, 'mediaAllStaff'])->name('pages.mediaAllStaff');
        Route::get('/requestMediaAllStaff', [MediaController::class, 'requestMediaAllStaff'])->name('request.mediaAllStaff');
    });

    Route::middleware('mediaCheck')->group(function () {
        // Media
        Route::get('/mediaGroup/{id}', [MediaController::class, 'rendermediaGroup'])->name('pages.mediaGroup');
        Route::get('/getMediaStaff/{id}', [MediaController::class, 'getMediaStaff'])->name('request.getMediaStaff');
        Route::post('/addMediaStaffRequest/{id}', [MediaController::class, 'addMediaStaff'])->name('request.addMediaStaff');
        Route::get('/getSpecificMediaStats', [MediaController::class, 'getSpecificMediaStats'])->name('request.getSpecificMediaStats');
        Route::post('/updateMediaStaffRequest/{staffId?}', [MediaController::class, 'updateMediaStaff'])->name('request.updateMediaStaffRequest');
        Route::get('/mediaGroup/{id}/addMediaStaff/{staffId?}', [MediaController::class, 'addMediaStaffRender'])->name('pages.addMediaStaffRender');
        Route::post('/mediaGroup/{id}/deleteMediaStaffRequest/{staffId}', [MediaController::class, 'deleteMediaStaffRequest'])->name('request.deleteMediaStaffRequest');
        Route::post('/updateMediaStaffSecurityStatus', [MediaController::class, 'updateMediaStaffSecurityStatus'])->name('request.updateMediaStaffSecurityStatus');
    });

    Route::middleware('depoUserCheck')->group(function () {
        // Depo
        Route::get('/depoGroups', [DepoGroupController::class, 'render'])->name('pages.depoGroups');
        Route::get('/getDepo', [DepoGroupController::class, 'getDepoGroups'])->name('request.getDepoGroups');
        Route::get('/getDepoStats', [DepoGroupController::class, 'getStats'])->name('request.getDepoStats');
        Route::get('/addDepoGroup/{id?}', [DepoGroupController::class, 'addDepoGroupRender'])->name('pages.addDepoGroup');
        Route::post('/addDepoRequest', [DepoGroupController::class, 'addDepoGroup'])->name('request.addDepoGroup');
        Route::post('/updateDepoGroup/{id}', [DepoGroupController::class, 'updateDepoGroup'])->name('request.updateDepoGroup');
    });

    Route::middleware('depoCheck')->group(function () {
        Route::get('/depoGroup/{id}', [DepoGroupController::class, 'depoGuestRender'])->name('pages.depoGroup');
        Route::get('/getDepoGuest/{id}', [DepoGroupController::class, 'getDepoGuest'])->name('request.getDepoGuest');
        Route::get('/depoGroup/{id}/addDepoGuestRender/{guestId?}', [DepoGroupController::class, 'addDepoGuestRender'])->name('pages.addDepoGuestRender');
        Route::post('/addDepoGuestRequest/{id}', [DepoGroupController::class, 'addDepoGroupGuest'])->name('request.addDepoGuest');
        Route::post('/import-depoGuest', [DepoGroupController::class, 'import'])->name('import.depoGuest');
        Route::post('/depoGroup/{id}/deleteDepoGuestRequest/{staffId}', [DepoGroupController::class, 'deleteDepoGuestRequest'])->name('request.deleteOrganizationStaff');
        Route::post('/updateDepoGuest/{id}', [DepoGroupController::class, 'updateDepoGuest'])->name('request.updateDepoGuest');
    });

    Route::middleware('orgCheck')->group(function () {
        // Organization
        Route::get('/organization/{id}', [OrganizationController::class, 'renderOrganisation'])->name('pages.organization');
        Route::get('/getOrganizationStaff/{id}', [OrganizationController::class, 'getOrganizationStaff'])->name('request.getOrganizationStaff');
        Route::get('/organization/{id}/addOrganizationStaff/{staffId?}', [OrganizationController::class, 'addOrganizationStaffRender'])->name('pages.addOrganizationStaff');
        Route::get('/getSpecificOrganizationStats', [OrganizationController::class, 'getSpecificOrganizationStats'])->name('request.getSpecificOrganizationStats');
        Route::post('/addOrganizationStaffRequest/{id}', [OrganizationController::class, 'addOrganizationStaff'])->name('request.addOrganizationStaff');
        Route::post('/updateOrganizationStaffRequest/{staffId?}', [OrganizationController::class, 'updateOrganizationStaff'])->name('request.updateOrganizationStaff');
        Route::post('/organization/{id}/deleteOrganizationStaffRequest/{staffId}', [OrganizationController::class, 'deleteOrganizationStaff'])->name('request.deleteOrganizationStaff');
        Route::post('/updateOrganisationStaffSecurityStatus', [OrganizationController::class, 'updateOrganisationStaffSecurityStatus'])->name('request.updateOrganisationStaffSecurityStatus');
    });

    Route::middleware('hrCheck')->group(function () {
        // Organization
        Route::get('/hrGroup/{id}', [HRController::class, 'renderHrGroup'])->name('pages.hrGroup');
        Route::get('/getHrGroupStaff/{id}', [HRController::class, 'getHrGroupStaff'])->name('request.getHrGroupStaff');
        Route::get('/hrGroup/{id}/addHrStaffRender/{staffId?}', [HRController::class, 'addHrGroupStaffRender'])->name('pages.addHrGroupStaffRender');
        Route::get('/getSpecificHrGroupStats', [HRController::class, 'getSpecificHrGroupStats'])->name('request.getSpecificHrGroupStats');
        Route::post('/addHrGroupStaffRequest/{id}', [HRController::class, 'addHrGroupStaff'])->name('request.addHrGroupStaff');
        Route::post('/updateHrGroupStaffRequest/{staffId?}', [HRController::class, 'updateHrGroupStaff'])->name('request.updateHrGroupStaff');
        Route::post('/hrGroup/{id}/deleteHrStaffRequest/{staffId}', [HRController::class, 'deleteHrStaffRequest'])->name('request.deleteHrStaffRequest');
        Route::post('/updateHrGroupStaffSecurityStatus', [HRController::class, 'updateHrGroupStaffSecurityStatus'])->name('request.updateHrGroupStaffSecurityStatus');
    });

    Route::middleware('attandeeUserCheck')->group(function () {
        Route::get('/attandee', [AttandeeController::class, 'render'])->name('pages.attandee');
    });

    Route::middleware('dataScanCheck')->group(function () {
        Route::resource('/dataScan', DataScanController::class)->names(['index' => 'pages.scan']);
    });

    Route::middleware('temporaryPassCheck')->group(function () {
        Route::resource('/temporaryPass', TemporaryPassController::class)->names([
            'index'   => 'temporaryPass.index',
            'create'  => 'temporaryPass.create',
            'show'    => 'temporaryPass.show',
            'edit'    => 'temporaryPass.edit',
            'destroy'    => 'temporaryPass.destroy',
        ]);
    });



    Route::middleware('snseaAdminCheck')->group(function () {

        Route::resource('governmentOrganization', GovernmentOrganizationController::class)->names([
            'index'   => 'governmentOrganization.index',
            'create'  => 'governmentOrganization.create',
            'show'    => 'governmentOrganization.show',
            'edit'    => 'governmentOrganization.edit',
        ]);

        Route::resource('governmentStaff', GovernmentStaffController::class)->names([
            'index'   => 'governmentStaff.index',
            'create'  => 'governmentStaff.create',
            'show'    => 'governmentStaff.show',
            'edit'    => 'governmentStaff.edit',
        ]);

        Route::resource('invitees', InviteesController::class)->names([
            'create'  => 'invitees.create',
            'edit'    => 'invitees.edit',
        ]);

        Route::get('/snseaDashboard', [SnSeaController::class, 'render'])->name('pages.snseaDashboard');

        Route::get('/snseaProgram', [ProgramController::class, 'render'])->name('pages.programs');

        Route::get('/addProgramPages/{uid?}', [ProgramController::class, 'addProgramPages'])->name('pages.addProgramPages');

        Route::get('/addCouponPages/{uid?}', [CouponsController::class, 'addCouponPages'])->name('pages.addCouponPages');

        Route::get('/snseaEssentials', [EssentialsController::class, 'render'])->name('pages.essentials');

        Route::get('/addCountryPage', [EssentialsController::class, 'addCountryPage'])->name('pages.addCountryPage');

        Route::get('/addCityPage', [EssentialsController::class, 'addCityPage'])->name('pages.addCityPage');

        Route::get('/addGroupPage', [EssentialsController::class, 'addGroupPage'])->name('pages.addGroupPage');

        Route::get('/events', function () {
            return view('pages.events');
        })->name('pages.events');

        Route::resource('templates', TemplateController::class)->names([
            'index'   => 'templates.index',
            'store'   => 'templates.store',
            'create'  => 'templates.create',
            'show'    => 'templates.show',
            'edit'    => 'templates.edit',
            'update'    => 'templates.update',
            'destroy'    => 'templates.destroy',
        ]);
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('pages.attendance.attendance');
        Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('request.attendance.mark');
        Route::get('/attendance/report/{event}', [AttendanceController::class, 'report'])->name('pages.attendance.report');
    });
});
