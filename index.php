<?php
session_start();
require("controllers/Routes.class.php");
require_once("controllers/Controller.class.php");
require_once("controllers/Users.class.php");

Route::set("index", function() {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
});

Route::set("sign_up", function() {
    UsersController::createView("create_user_form");
});

if (isset($_POST) && array_key_exists("submit_create", $_POST)) {
    echo UsersController::create_user($_POST);
}

if (!in_array($_GET["url"], Route::$validRoutes)) {
    require_once("views/header.module.php");
    require_once("views/index.view.php");
    require_once("views/footer.module.php");
}
