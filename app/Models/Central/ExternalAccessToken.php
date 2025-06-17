<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class ExternalAccessToken extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql';  // Substitua 'central' pelo nome da sua conexão central conforme configurado.
}
