<?php

namespace App\Exceptions;

use App\Services\DiscordService;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (\Throwable $e) {
            // if ($this->shouldReport($e)) {
            //     $clientDiscord = new GuzzleClient();
            //     $discord = new DiscordService($clientDiscord);
            //     $discord->sendError($e, 'Laravel Handler');
            // }
        });
    }
}
