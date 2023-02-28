<?php

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

return [
    'STEAM_API_KEY' => "XXXXXXXXXXXXXXX", // Generate on https://steamcommunity.com/dev/apikey
    'LOGO_PATH' => "img/logo.webp",
    'FTP' => [

        'Server1' => [ // ServerName
            'host' => "127.0.0.1", // FTP Host
            'username' => "user", // FTP User
            'password' => "pass", // FTP Password
            'path' => "/csgo/addons/sourcemod/configs/admins_simple.ini", // FTP Path
        ],
        
    ],
    'ADMIN_FLAG' => ["d", "@Admin", "@Opiekun", "@Wlasciciel"], // Admin flags and groups
    'CACHE_TIME' => 5 * 60, // How long cache page
    'CACHE_DIR' => "cache/", // Cache Dir
    'CACHE_FILENAME' => md5($_SERVER['REQUEST_URI']), // Cache Filename
];
