<?php
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: ./pages/user_homepage.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Cloud Authentication</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico" />

        <script src="assets/js/pace.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/css/pace.minimal.css" />

        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    </head>
    <body>
        <div class="container">
            <div class="alert alert-danger fade show offset-lg-2 col-lg-8" id="alertLogin" role="alert">
                <strong>Error.</strong> Incorrect login and/or password.
            </div>

            <h1 class="offset-lg-4">Authentication</h1>

            <div class="col-lg-6 offset-lg-3">
                <form id="loginForm">
                    <div class="form-group row">
                        <label for="login" class="col-sm-2 col-form-label">Login*</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="text" class="form-control" name="login" id="login" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password*</label>
                        <div class="col-sm-10 col-lg-6">
                            <input type="password" class="form-control" name="password" id="password" required />
                        </div>
                    </div>
                    <input class="btn btn-primary offset-lg-4" type="submit" id="btnSubmit" value="Submit" />
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal" id="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content panel-warning">
                    <div class="modal-header paner-heading">
                        <h5 class="modal-title">Server error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="errorBody"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script>
            jQuery(function($) {
                $("#loginForm").submit(function(event) {
                    event.preventDefault();

                    var login = $("#login").val();

                    var button = $("#btnSubmit");
                    button.prop("disabled", true);

                    $.ajax({
                        url: "./pages/php/ldap_search.php",
                        type: "POST",
                        data: {
                            login: login,
                            password: $("#password").val()
                        }
                    }).done(function(data) {
                        // Successfully logged  
                        window.location.replace("pages/user_homepage.php");
                    }).fail(function(jqXHR, textStatus, error) {
                        console.log(jqXHR.responseText);
                        if (jqXHR.status == 401 || (jqXHR.responseText && jqXHR.responseText.includes("Invalid credentials"))) {
                            // Bad credentials
                            $("#alertLogin").css("display", "block");
                        } else {
                            // Another error
                            $("#errorBody").html(error);
                            $("#modal").modal();
                        }
                    }).always(function() {
                        button.prop("disabled", false);
                    });
                });
            });
        </script>
    </body>
</html>