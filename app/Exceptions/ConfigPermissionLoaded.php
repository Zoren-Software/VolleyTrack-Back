<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ConfigPermissionLoaded extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return null;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(): Response
    {
        return response('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.', 500);
    }
}
