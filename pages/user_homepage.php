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
        <link rel="stylesheet" type="text/css" href="../assets/css/datatables.bootstrap4.min.css" />
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
            <h3>Overview</h3>
            <hr>
            <div class="col" id="overview"></div>
            <div class="col">
                <h5>Liste des machines associées</h5>
                <table class="table table-sm table-hover" id="systemTable">
                    <thead>
                        <tr>
                            <th>Group</th>
                            <th>Name</th>
                            <th>OS</th>
                            <th>Version</th>
                            <th>Architecture</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/jquery.datatables.min.js"></script>
        <script src="../assets/js/datatables.bootstrap4.min.js"></script>
        <script>
            var allSystems = Array();
            var userSystems = Array();

            jQuery(function($) {
                // Get user Systems
                $.ajax({
                    url: './php/getUserSystems.php',
                    type: 'POST',
                    data: {
                        user: '<?php echo $user ?>'
                    }
                }).done(function(data) {
                    var json = JSON.parse(data);
                    for (var system of json) {
                        // Get User group
                        var usergroup = {};
                        for (var group of system.paths[0]) {
                            if (group.to.type == "user_group") {
                                usergroup.id = group.to.id;
                                usergroup.name = group.to.attributes.ldapGroups[0].name;
                                break;
                            }
                        }

                        userSystems.push({
                            id: system.id,
                            groupid: usergroup.id,
                            groupname: usergroup.name
                        });
                    }
                    
                    // Get all systems data
                    $.ajax({
                        url: './php/getSystems.php',
                        type: 'GET'
                    }).done(function(data) {
                        var json = JSON.parse(data);
                        for (var system of json.results) {
                            allSystems.push(system);
                        }

                        // Both succeed: build table
                        for (var i = 0; i < userSystems.length; i++) {
                            var systemInfo = allSystems.find(function(sys) {
                                return sys.id === userSystems[i].id;
                            });
                            userSystems[i].name = systemInfo.hostname;
                            userSystems[i].os = systemInfo.os;
                            userSystems[i].version = systemInfo.version;
                            userSystems[i].architecture = systemInfo.arch;
                        }

                        // Display table
                        $("#systemTable").DataTable({
                            data: userSystems,
                            columns: [
                                { data: "groupname" },
                                { data: "name" },
                                { data: "os" },
                                { data: "version" },
                                { data: "architecture" }
                            ],
                            lengthMenu: [5, 10, 25],
                            language: {
                                url: "../assets/french.json"
                            },
                            order: [[0, 'asc']]
                        });
                    }).fail(function(jqXHR, textStatus, error){
                        console.log("GetAllSystems: " + error);
                        console.log("Status: " + jqXHR.status);
                    });
                }).fail(function(jqXHR, textStatus, error) {
                    console.log("GetUserSystems: " + error);
                    console.log("Status: " + jqXHR.status);
                });
                
                // Get user Groups
                /*$.ajax({
                    url: './php/getUserGroups.php',
                    type: 'POST',
                    data: {
                        user: '<?php echo $user ?>'
                    }
                }).done(function(data) {
                    var json = JSON.parse(data);
                    console.log(json);
                    var overview = $("#overview");
                    if (json.length > 0) {
                        overview.html("<p>Nombre de groupes : " + json.length + "</p>");
                        for(var group of json) {
                            // Liste des groupes à afficher
                            console.log("id: " +group.id + ", name: " + group.paths[0][0].to.attributes.ldapGroups[0].name);
                            groups.push({
                                id: group.id,
                                name: group.paths[0][0].to.attributes.ldapGroups[0].name;
                            });
                        }
                    } else {
                        overview.html("<p>Aucun Groupe d'utilisateurs associé.</p>");
                    }
                }).fail(function(jqXHR, textStatus, error) {
                    console.log("GetUserGroups: " + error);
                    console.log("Status: " + jqXHR.status);
                });*/
            });
        </script>
    </body>
</html>