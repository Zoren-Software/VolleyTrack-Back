<?php

namespace App\Exceptions;

use Exception;

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
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.';
    }
}
