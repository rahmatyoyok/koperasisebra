<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;


class ItemQuantity extends Model
{
	protected $table = 'ospos_item_quantities';
    public $timestamps = false;
	protected $primaryKey = 'item_id';

 

}
