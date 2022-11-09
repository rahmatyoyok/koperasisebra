<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
	protected $table = 'ospos_items';
    public $timestamps = false;
	protected $primaryKey = 'item_id';

 

}
