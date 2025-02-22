<?php

namespace App\Exceptions;

use Exception;

class PermissionConfigNotLoadedException extends Exception
{
    // Defina uma mensagem padrão, se desejar.
    protected $message = 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.';
}
