<?php
namespace App\classes;

class Helpers{
    
    public function dd(mixed $data): void
    {
        echo '
    <pre>';
        if (is_array($data) || is_object($data)) {
            print_r($data);
        } else {
            var_dump($data);
        }
        echo '</pre>';
        die();
    }

    public function flash($key, $message = null)
    {
        if ($message) {
            $_SESSION['flash'][$key] = $message;
        }
        else if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
    }


    

}