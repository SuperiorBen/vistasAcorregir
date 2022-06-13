<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIdentifiers extends Model
{
    protected $table = 'user_identifiers';
    protected $primaryKey = 'PRIMARY';

    protected $connection = 'chivo-ap';
}
