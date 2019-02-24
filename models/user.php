<?php
namespace AGD\Wis;

class User
{
    public $id;
    public $username;
    public $password;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->username = $data['username'] ?? null;
            $this->password = $data['password'] ?? null;
        }
    }
}