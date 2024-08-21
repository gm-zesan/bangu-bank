<?php
    namespace App\classes;

    class Customer extends User{
        use ValidateTrait;

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
    }
?>