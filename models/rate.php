<?php
namespace AGD\Wis;

class Rate
{
    public $id;
    public $description;
    public $rate;
    public $package;    
    public $addon; 

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->description = $data['description'] ?? null;
            $this->rate = $data['rate'] ?? null;
            $this->package = $data['package'] ?? null;
            $this->addon = $data['addon'] ?? null;
        }
    }
}