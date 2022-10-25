<?php
namespace App;
class Inventory
{
    private $inventory;

    public function setAmount(int $inventory)
    {
        $this->inventory = $inventory;
    }

    public function result(int $inventory){
        if($inventory < 10){
            return "less";
        }else{
            return "enough";
        }
    }

    public function checkInventory()
    {
        return $this->result($this->inventory);
    }
}