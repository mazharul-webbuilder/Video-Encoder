<?php

function active_class($path, $active = 'active') {
  return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_active_route($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

/**
 *
 * Exclude  file extension from file name
 */
if (!function_exists('getHumanReadableFilename')){
    function getHumanReadableFilename(string $fileName): string
    {
        return substr($fileName, 0, strrpos($fileName, "."));

    }
}
