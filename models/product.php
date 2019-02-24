<?php
namespace AGD\Wis;

class Product
{
    public $id;
    public $category;
    public $title;
    public $description;    
    public $photo;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->category = $data['category'] ?? null;
            $this->title = $data['title'] ?? null;
            $this->description = $data['description'] ?? null;
            $this->photo = $data['photo'] ?? null;
        }
    }
}