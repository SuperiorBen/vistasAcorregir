<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConfigs extends Model
{
    protected $table = 'user_configs';
    protected $primaryKey = 'PRIMARY';

    protected $connection = 'chivo-ap';
}
