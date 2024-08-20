<?php

    namespace App\classes;

    class FileStorage
    {
        protected $filePath;
        public function __construct() {
            $this->filePath = __DIR__ . '../../data/users.json';
        }
        public function load($file){
            if (!file_exists($file)) {
                return [];
            }
            return json_decode(file_get_contents($file), true);
        }

        public function save($file, $data){
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        }

        public function getUserByEmail($email) {
            $users = json_decode(file_get_contents($this->filePath), true);
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    return $user;
                }
            }
            return null;
        }
    }
