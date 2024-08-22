<?php
    namespace APP\classes;
    use App\classes\FileStorage;
    use App\classes\ValidateTrait;
    class Transaction{
        use ValidateTrait;
        
        protected $fileStorage;
        protected $errors = array();

        public function __construct()
        {
            $this->fileStorage = new FileStorage();
        }

        public function transfer($amount, $fromEmail, $toEmail){
            $this->errors = [];
            if($this->validate('amount', $amount) === false){
                $this->errors[] = array('error' => 'Amount is required', 'field' => 'amount');
            }
            if($this->validate('email', $toEmail) === false){
                $this->errors[] = array('error' => 'Recipient email is required', 'field' => 'email');
            }

            if(!empty($this->errors)){
                return $this->errors;
            }

            $users = $this->fileStorage->load(__DIR__ . '../../data/users.json');
            $recipientFound = false;

            foreach ($users as $key => $user) {
                if ($user['email'] === $fromEmail) {
                    if ($user['balance'] < $amount) {
                        return false;
                    }
                    $users[$key]['balance'] -= $amount;
                }
                if($user['email'] === $toEmail){
                    $users[$key]['balance'] += $amount;
                    $recipientFound = true;
                }
            }

            if (!$recipientFound) {
                return false;
            }

            $this->fileStorage->save(__DIR__ . '../../data/users.json', $users);
            $this->logTransaction($fromEmail, 'transfer', $amount, $toEmail);
            $this->logTransaction($toEmail, 'received', $amount, $fromEmail);

            return true;
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

        public function getTransactionByUser($email)
        {
            $transactions = $this->fileStorage->load(__DIR__ . '../../data/transactions.json');
            $userTransactions = [];
            foreach($transactions as $transaction){
                if($transaction['email'] === $email || (isset($transaction['recipient']) && $transaction['recipient'] === $email)){
                    $userTransactions[] = $transaction;
                }
            }
            return $userTransactions;
        }
    }
