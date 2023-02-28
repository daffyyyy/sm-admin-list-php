<?php
require_once(__DIR__ . '/src/Admins.class.php');

$server = $_GET['server'] ?? NULL;
$class = new Admins($server);

$class->validCache();
$admins = $class->parseAdmins();

ob_start();
?>

<!doctype html>
<html lang="en">

<head>
    <title>:: Server admins of <?php echo $server; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                    <img class="d-block mx-auto mb-4" src="<?php echo $class->getLogo(); ?>" alt="" width="300" height="250">
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
                    $adminInfo = $class->parsePlayer($admin);
                ?>
                    <div class="col-md-2 card text-center py-2 mb-2">
                        <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="dark">
                            <img src="<?php echo $adminInfo->avatarfull; ?>" class="img-fluid" />
                            <a href="#!">
                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15)"></div>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $adminInfo->personaname; ?></h5>
                            <p class="card-text"><?php echo $adminInfo->status; ?></p>
                            <a href="<?php echo $adminInfo->profileurl; ?>" class="btn btn-primary">Go to profile</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script type="text/javascript" src="js/mdb.min.js"></script>
</body>

</html>

<?php
$class->writeCache(ob_get_contents());
ob_end_flush();
?>