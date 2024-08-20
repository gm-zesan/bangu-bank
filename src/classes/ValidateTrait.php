<?php

namespace App\classes;

trait ValidateTrait
{
    private function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    private function validate($name, $data)
    {
        if ($name === 'name' && !empty($data)) {
            return $this->sanitize($data);
        }
        if ($name === 'email' && !empty($data)) {
            return filter_var($this->sanitize($data), FILTER_VALIDATE_EMAIL);
        }
        if ($name === 'password' && !empty($data)) {
            return $this->sanitize($data);
        }
        if ($name === 'amount' && !empty($data)) {
            return (float) $this->sanitize($data);
        }
        return false;
    }
}
