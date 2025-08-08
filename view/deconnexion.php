<?php
session_start(); // Démarre la session


$_SESSION = [];

// Si vous souhaitez détruire complètement la session, effacez également le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header("Location: login.php");
exit();
?>
