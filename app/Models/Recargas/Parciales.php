<?php

namespace App\Models\Recargas;

use Illuminate\Database\Eloquent\Model;

class Parciales extends Model
{
    protected $table = 'parciales';
    protected $primaryKey = 'PRIMARY';

    protected $connection = 'chivo-recargas';
}
