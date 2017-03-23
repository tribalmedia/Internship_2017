<?php

if (isset($_GET['oauth_token']) || isset($_GET["oauth_verifier"])) {

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'authentication.php';

} elseif (isset($_GET["denied"])) {

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'denied.php';

} else {

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

}