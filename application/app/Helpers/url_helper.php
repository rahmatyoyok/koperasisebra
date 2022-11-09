<?php
if (! function_exists('assets')) {
    function assets($path, $secure = null)
    {
        return asset('application/resources/assets/' . trim($path, '/'), $secure);
    }
}