<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    Protected $fillable = ['tax_rate','tax_inclusion','discount_fixed','discount_percent'];
    Protected $primaryKey = 'id';
}
