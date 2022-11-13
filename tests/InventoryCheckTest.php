<?php


class InventoryCheckTest extends \PHPUnit\Framework\TestCase
{

    function setUp(): void{
        // $inventory = new App\Inventory;
    }

    public function testMinAmount()
    {
        $inventory = new App\Inventory;
        $inventory->setAmount(5);
        $this->assertEquals("less", $inventory->checkInventory());
    }
}