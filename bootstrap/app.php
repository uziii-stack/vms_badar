<?php

use App\Http\Middleware\{DataScanUser, AllowFromFrontend, TemporaryPassUser, Organization, AttandeeUser, MediaGroup, DepoGroup, BxssUser, HRGroup, Admin, Media, SnSea, Depo, AllowIframeMiddleware, RefreshSessionUser};
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [
            RefreshSessionUser::class,
        ]);
        $middleware->appendToGroup('orgCheck', [
            Organization::class,
        ]);
        $middleware->appendToGroup('hrCheck', [
            HRGroup::class,
        ]);
        $middleware->appendToGroup('mediaCheck', [
            MediaGroup::class,
        ]);
        $middleware->appendToGroup('adminCheck', [
            Admin::class,
        ]);
        $middleware->appendToGroup('mediaUserCheck', [
            Media::class,
        ]);
        $middleware->appendToGroup('depoUserCheck', [
            Depo::class,
        ]);
        $middleware->appendToGroup('depoCheck', [
            DepoGroup::class,
        ]);
        $middleware->appendToGroup('bxssCheck', [
            BxssUser::class,
        ]);
        $middleware->appendToGroup('attandeeUserCheck', [
            AttandeeUser::class,
        ]);
        $middleware->appendToGroup('snseaAdminCheck', [
            SnSea::class,
        ]);
        $middleware->appendToGroup('temporaryPassCheck', [
            TemporaryPassUser::class,
        ]);
        $middleware->appendToGroup('dataScanCheck', [
            DataScanUser::class,
        ]);
        $middleware->appendToGroup('allowFromFrontend', [
            AllowFromFrontend::class,
        ]);
        $middleware->appendToGroup(
            'allowIframe',
            [
                AllowIframeMiddleware::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
