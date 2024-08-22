<?php
    namespace APP\classes;
    use App\classes\FileStorage;
    use App\classes\Transaction;
    use App\classes\Helpers as ClassesHelpers;
    use App\classes\ValidateTrait;


    class User extends ClassesHelpers {
        use ValidateTrait;

        protected $email;
        protected $balance;
        protected $fileStorage;
        protected $transaction;
        protected $errors = array();

        public function __construct() {
            $this->fileStorage = new FileStorage();
            $this->transaction = new Transaction();
        }
        
        public function register($name, $email, $password){
            if($this->validate('name', $name) === false){
                $this->errors[] = array('error' => 'Name is required', 'field' => 'name');
            }
            if($this->validate('email', $email) === false){
                $this->errors[] = array('error' => 'Email is required', 'field' => 'email');
            }
            if($this->validate('password', $password) === false){
                $this->errors[] = array('error' => 'Password is required', 'field' => 'password');
            }
            if(!empty($this->errors)){
                return $this->errors;
            }
            $users = $this->fileStorage->load(__DIR__ . '../../data/users.json');
            foreach($users as $user){
                if($user['email'] === $email){
                    return false;
                }
            }
            $users[] = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'balance' => 0,
                'isAdmin' => false
            ];
            $this->fileStorage->save(__DIR__ . '../../data/users.json', $users);
            return true;
        }



        public function login($email, $password){
            if($this->validate('email', $email) === false){
                $errors[] = array('error' => 'Email is required', 'field' => 'email');
            }
            if($this->validate('password', $password) === false){
                $errors[] = array('error' => 'Password is required', 'field' => 'password');
            }
            if(!empty($errors)){
                return $errors;
            }
            $users = $this->fileStorage->load(__DIR__ . '../../data/users.json');
            foreach($users as $user){
                if ($user['email'] === $email && password_verify($password, $user['password'])) {
                    $this->email = $email;
                    $this->balance = $user['balance'];
                    return $user;
                }
            }
            return false;
        }

        public function getAllUsers(){
            $users = $this->fileStorage->load(__DIR__ . '../../data/users.json');
            return $users;
        }

        public function getUserNameByEmail($email) {
            if($email){
                $userData = $this->fileStorage->getUserByEmail($email);
                if($userData){
                    return $userData['name'];
                }
            }
            return 'Unknown';
        }



        
        public function getInitials($fullName) {
            $nameParts = explode(' ', $fullName);
            if (count($nameParts) > 1) {
                $initials = '';
                foreach ($nameParts as $part) {
                    $initials .= strtoupper($part[0]);
                }
                return substr($initials, 0, 2);
            } else {
                return strtoupper(substr($fullName, 0, 2));
            }
        }

        
        public function getBalance($email){
            if ($email) {
                $userData = $this->fileStorage->getUserByEmail($email);
                if ($userData) {
                    $this->balance = $userData['balance'];
                }
            }
            return $this->balance;
        }

        public function deposit($amount, $email){
            if($this->validate('amount', $amount) === false){
                return false;
            }
            if ($amount <= 0) {
                return false;
            }
            $this->balance += $amount;
            $this->updateUserBalance($this->balance, $email);
            $this->transaction->logTransaction($email, 'deposit', $amount);
            return true;
        }

        public function withdraw($amount, $email){
            if($this->validate('amount', $amount) === false){
                return false;
            }
            if ($this->balance < $amount || $amount <= 0) {
                return false;
            }
            $this->balance -= $amount;
            $this->updateUserBalance($this->balance, $email);
            $this->transaction->logTransaction($email, 'withdraw', $amount);
            return true;
        }

        
        private function updateUserBalance($balance, $email){
            $users = $this->fileStorage->load(__DIR__ . '../../data/users.json');
            foreach ($users as $index => $user) {
                if ($user['email'] === $email) {
                    $users[$index]['balance'] = $balance;
                    $this->balance = $balance;
                    break;
                }
            }
            $this->fileStorage->save(__DIR__ . '../../data/users.json', $users);
        }
    }
?>