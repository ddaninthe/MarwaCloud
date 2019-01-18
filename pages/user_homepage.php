<?php
    require_once(__DIR__ . '/php/utils.php');

    session_start();
    //$_SESSION['user'] = "5c017702555f9d1b6a97718f";
    $user = $_SESSION['user'];
    echo $user;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Cloud Authentication</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico" />

        <script src="../assets/js/pace.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../assets/css/pace.minimal.css" />

        <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../assets/css/style.css" />
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="navbarNavDropdown" class="navbar-collapse collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Groups</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Systems</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="location.href='disconnect.php'">Disconnect</a>
                        </li>
                    </ul>
                </div>
            </nav>
        <div class="container">
            <h3>My Groups</h3>
            <hr>
            <h3>My Systems</h3>
        </div>
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script>
            jQuery(function($) {
                $.ajax({
                    url: './php/getUserGroups.php',
                    type: 'POST',
                    data: {
                        user: '<?php echo $user ?>'
                    }
                }).done(function(data) {
                    var json = JSON.parse(data);
                    // TODO: check empty
                    console.log(data);
                    for(var group of json[0].paths[0][0].to.attributes.ldapGroups) {
                        // Liste des groupes à afficher
                        console.log(group.name);
                    }
                }).fail(function(jqXHR, textStatus, error) {
                    console.log(error);
                    console.log("Status: " + jqXHR.status);
                });     
            });
        </script>
    </body>
</html>