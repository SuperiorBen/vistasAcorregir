<?php

namespace App\Models\Recargas;

use Illuminate\Database\Eloquent\Model;

class Totales extends Model
{
    protected $table = 'totales';
    protected $primaryKey = 'PRIMARY';

    protected $connection = 'chivo-recargas';

    protected $fillable = [
        'dni',
        'cc_id',
        'moneda_id',
        'total',
        'fecha'
    ];
}
