<?php
// Load config
$config = require('config.php');

// Check if server is in config
$server = $_GET['server'] ?? NULL;
if (array_key_exists($server, $config['FTP']) === FALSE) {
    die("Brak takiego serwera!");
}

$cache_created  = (file_exists($config['CACHE_DIR'] . $config['CACHE_FILENAME'])) ? filemtime($config['CACHE_DIR'] . $config['CACHE_FILENAME']) : 0;

if ((time() - $cache_created) < $config['CACHE_TIME']) {
    print str_replace(array("\n", "\r", "\t"), '', file_get_contents($config['CACHE_DIR'] . $config['CACHE_FILENAME']));
    exit();
}

ob_start();
$admins = [];

// Open FTP connection
$admins_file = fopen(sprintf('ftp://%s:%s@%s/%s', $config['FTP']['MIRAGE']['username'], $config['FTP']['MIRAGE']['password'], $config['FTP']['MIRAGE']['host'], $config['FTP']['MIRAGE']['path']), 'r');

// Iterate stream
while (!feof($admins_file)) {
    // Get line from stream
    $line = stream_get_line($admins_file, 4096, "\n");

    // Check if line starts with comment
    if (strlen($line) < 10 || str_starts_with($line, "//") || str_starts_with($line, ";")) continue;
    $exp = explode("\"", $line);

    // Check if user has admin flag or group
    if (in_array($exp[3], $config['ADMIN_FLAG'])) {
        $admins[] = $exp[1];
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="css/mdb.dark.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Hero -->
    <section id="hero">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <img class="d-block mx-auto mb-4" src="<?php echo $config['LOGO_PATH']; ?>" alt="" width="300" height="250">
                    <h2 class="text-white font-weight-bold display-5">List of <strong><?php echo $server; ?></strong> server admins</h1>
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5">Here you will find a list of all admins of our server!</p>
                    <a class="btn btn-primary" href="#admins">Show</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Admins -->
    <section id="admins">
        <div class="container h-100">
            <div class="row h-100 text-center">
                <?php foreach ($admins as $admin) :
                    $steamid = explode(":", $admin);
                    $steamid64 = bcadd((($steamid[2] * 2) + $steamid[1]), '76561197960265728');
                    $info = json_decode(file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$config['STEAM_API_KEY']}&steamids={$steamid64}"))->response->players[0];
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
                ?>
                    <div class="col-md-2 card text-center py-2 mb-2">
                        <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="dark">
                            <img src="<?php echo $info->avatarfull; ?>" class="img-fluid" />
                            <a href="#!">
                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15)"></div>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $info->personaname; ?></h5>
                            <p class="card-text"><?php echo $status; ?></p>
                            <a href="<?php echo $info->profileurl; ?>" class="btn btn-primary">Go to profile</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script type="text/javascript" src="js/mdb.min.js"></script>
</body>

</html>

<?php
    file_put_contents($config['CACHE_DIR'] . $config['CACHE_FILENAME'], ob_get_contents());
    ob_end_flush();
?>