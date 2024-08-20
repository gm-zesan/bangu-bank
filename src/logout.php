<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use App\classes\Session;
    $session = new Session();
    $session->start();
    $session->destroy();
    header('Location: ./../index.php');
    exit();
?>
