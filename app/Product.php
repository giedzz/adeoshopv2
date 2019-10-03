<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

            

class Product extends Model
{
    Protected $fillable = ['name','sku','status','base_price', 'special_price', 'image', 'description'];
    Protected $primaryKey = 'id';
}
