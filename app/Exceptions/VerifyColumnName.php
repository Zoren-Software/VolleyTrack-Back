<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class VerifyColumnName extends Exception
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
    public function render(): Response
    {
        return response('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.', 500);
    }
}
