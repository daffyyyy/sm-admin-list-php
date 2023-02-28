<?php

class Admins
{
    private $config;
    public $server;

    public function __construct($server)
    {
        $this->config = require(__DIR__ . '/config.php');
        $this->server = $server;
        if ($server === NULL || array_key_exists($server, $this->getConfig("FTP")) === FALSE) {
            die("Bad server parameter!");
        }
    }

    private function getConfig(string $part = NULL)
    {
        if ($part === NULL) {
            return $this->config;
        }

        return $this->config[$part];
    }

    public function getLogo(): string
    {
        return $this->getConfig("LOGO_PATH");
    }

    private function getAdminsFile()
    {
        $fp = fopen(sprintf('ftp://%s:%s@%s/%s', $this->getConfig("FTP")[$this->server]['username'], $this->getConfig("FTP")[$this->server]['password'], $this->getConfig("FTP")[$this->server]['host'], $this->getConfig("FTP")[$this->server]['path']), 'r');

        if (!$fp) {
            throw new Exception('Problem with FTP connection');
        }

        return $fp;
    }

    public function parseAdmins(): array
    {
        $admins = [];
        $stream = $this->getAdminsFile();
        while (!feof($stream)) {
            // Get line from stream
            $line = stream_get_line($stream, 4096, "\n");

            // Check if line starts with comment
            if (strlen($line) < 10 || str_starts_with($line, "//") || str_starts_with($line, ";")) continue;
            $exp = explode("\"", $line);

            // Check if user has admin flag or group
            if (in_array($exp[3], $this->getConfig("ADMIN_FLAG"))) {
                $admins[] = $exp[1];
            }
        }
        return $admins;
    }

    public function parsePlayer(string $steamid): object
    {
        $steamid = explode(":", $steamid);
        $steamid = bcadd((($steamid[2] * 2) + $steamid[1]), '76561197960265728');

        $info = json_decode(file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$this->GetConfig("STEAM_API_KEY")}&steamids={$steamid}"))->response->players[0];

        switch ($info->personastate) {
            case 0:
            case 3:
            case 4:
                $status = "<span class=\"badge text-bg-dark\">Offline</span>";
                break;
            case 1:
            case 6:
                $status = "<span class=\"badge text-bg-success\">Online</span>";
                break;
            default:
                $status = "<span class=\"badge text-bg-dark\">Offline</span>";
                break;
        }
        $info->status = $status;

        return $info;
    }

    public function validCache()
    {
        $cacheTime = (file_exists($this->getConfig("CACHE_DIR") . $this->getConfig("CACHE_FILENAME"))) ? filemtime($this->getConfig("CACHE_DIR") . $this->getConfig("CACHE_FILENAME")) : 0;

        if ((time() - $cacheTime) < $this->getConfig("CACHE_TIME")) {
            print str_replace(array("\n", "\r", "\t"), '', file_get_contents($this->getConfig("CACHE_DIR") . $this->getConfig("CACHE_FILENAME")));
            exit;
        }
    }

    public function writeCache(string $buffer): void
    {
        file_put_contents($this->getConfig("CACHE_DIR") . $this->getConfig("CACHE_FILENAME"), $buffer);
    }
}
