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


    function logTransaction($email, $type, $amount, $recipient = null) {
        $file = '../../data/transactions.json';
        $transactions = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $transactions[] = [
            'email' => $email,
            'type' => $type,
            'amount' => $amount,
            'date' => date('d M Y, H:i:s'),
            'recipient' => $recipient
        ];
        file_put_contents($file, json_encode($transactions, JSON_PRETTY_PRINT));
    }

}