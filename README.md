## 🛡️ sm-admin-list-php

<p  align="center">
<a  href="#description">Description 📄</a> | 
<a  href="#configuration">Configuration 🛠</a> | 
<a  href="#live">Live 🛠</a> | 
<a  href="#requirements">Requirements !</a> 
</p>

---

# discontinued
> Counter-Strike Global-Offensive changed to Counter-Strike 2, sourcemod which project used is unusuable on source2 engine.

### Description
- The script displays the admins from the admins_simple.ini file
- Multiple servers can be added

### Configuration
<details>
<summary><b>src/config.php</b></summary>

```php
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
```
To add more servers, duplicate `Server1` entry e.g
```php
        'Server1' => [ // ServerName
            'host' => "127.0.0.1", // FTP Host
            'username' => "user", // FTP User
            'password' => "pass", // FTP Password
            'path' => "/csgo/addons/sourcemod/configs/admins_simple.ini", // FTP Path
        ],
        'Server2' => [ // ServerName
            'host' => "127.0.0.1", // FTP Host
            'username' => "user", // FTP User
            'password' => "pass", // FTP Password
            'path' => "/csgo/addons/sourcemod/configs/admins_simple.ini", // FTP Path
        ],
```
Then visit `SITE_URL/index.php?server=Server1` or `SITE_URL/index.php?server=Server2`
</details>

### Live
[Visit](https://utopiafps.pl/admins/index.php?server=MIRAGE)

### Requirements
- PHP >= 7
