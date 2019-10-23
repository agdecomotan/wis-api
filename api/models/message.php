<?php
namespace AGD\Wis;

class Message
{
    public $id;
    public $name;
    public $email;
    public $contact;    
    public $message; 
    public $datecreated;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->name = $data['name'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->contact = $data['contact'] ?? null;
            $this->message = $data['message'] ?? null;
            $this->datecreated = $data['datecreated'] ?? null;
        }
    }
}