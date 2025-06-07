<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ConfigPermissionLoadedForDrop extends Exception
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
        return response(
            'Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.',
            500
        );
    }
}
