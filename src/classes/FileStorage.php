<?php

    namespace App\classes;

    class FileStorage
    {
        public function load($file)
        {
            if (!file_exists($file)) {
                return [];
            }
            return json_decode(file_get_contents($file), true);
        }

        public function save($file, $data)
        {
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        }
    }
