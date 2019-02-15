<?php
    require_once(__DIR__ . '/php/utils.php');

    session_start();
    $user = $_SESSION['user'];
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
        <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="./user_homepage.php">LDAP Dashboard</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="navbarNavDropdown" class="navbar-collapse collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">My Groups</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="disconnect.php">Disconnect</a>
                        </li>
                    </ul>
                </div>
            </nav>
        <div class="container">
            <h3>List of my groups</h3>
            <hr>
            <div class="col" id="myGroups"></div>
        </div>
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script>
            var userGroups = Array();

            jQuery(function($) {
                // Get user Groups
                $.ajax({
                    url: './php/getUserGroups.php',
                    type: 'POST',
                    data: {
                        user: '<?php echo $user ?>'
                    }
                }).done(function(data) {
                    var json = JSON.parse(data);
                    for (var system of json) {
                        // Get User group
                        for (var group of system.paths[0]) {
                            if (group.to.type == "user_group") {
                                var groupName = group.to.attributes.ldapGroups[0].name;
                                if (userGroups.indexOf(groupName) < 0) {
                                    userGroups.push(groupName);
                                }
                            }
                        }
                    }
                    
                    // Display all groups
                    var htmlGroups = "<p>";
                    if (userGroups.length > 0) {
                        htmlGroups +=  "<u>" + userGroups.length + " Group(s) :</u></p><ul>";
                        for (var groupName of userGroups) {
                            htmlGroups += "<li>" + groupName + "</li>"
                        }
                        htmlGroups += "</ul>"
                    } else {
                        htmlGroups += "Not member of any groups.</p>";
                    }

                    $("#myGroups").html(htmlGroups);
                   
                }).fail(function(jqXHR, textStatus, error) {
                    console.log("GetUserGroups: " + error);
                    console.log("Status: " + jqXHR.status);
                });
            });
        </script>
    </body>
</html>