<?php

namespace App\Exceptions;

use Exception;

class PermissionConfigNotLoadedException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.';
}
