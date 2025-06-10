<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'domain',
        'tenant_id',
    ];
}
