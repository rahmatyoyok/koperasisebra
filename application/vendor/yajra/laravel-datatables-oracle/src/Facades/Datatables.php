<?php

namespace Yajra\Datatables\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Datatables.
 *
 * @package Yajra\Datatables\Facades
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Datatables extends Facade
{
    
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
        return 'datatables';
    }
}
