<?php
    namespace APP\classes;
    use App\classes\FileStorage;
    class User{
        protected $fileStorage;


        public function __construct(){
            $this->fileStorage = new FileStorage();
        }
        
        public function register($name, $email, $password){
            $users = $this->fileStorage->load(__DIR__ . '../../data/users.json');

            foreach($users as $user){
                if($user['email'] === $email){
                    return ['error' => 'User already exists'];
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

            return ['success' => 'User registered successfully'];
        }
    }
?>