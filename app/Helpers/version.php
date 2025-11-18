<?php

if (!function_exists('app_version')) {
    function app_version() {
        return config('version.version');
    }
}

if (!function_exists('app_author')) {
    function app_author() {
        return config('version.author');
    }
}

if (!function_exists('app_last_updated')) {
    function app_last_updated() {
        return config('version.last_updated');
    }
}
