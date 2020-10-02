<?php
/**
 * Created by PhpStorm.
 * User: gumonteilh
 * Date: 01/10/20
 * Time: 06:32
 */

/*
 * Question 1
 */

namespace tp4;

use PHPUnit\Framework\TestCase;

require_once('src/Money.php');

class MoneyTest1 extends TestCase
{
    protected $money;

    /**
     * @before
     */
    public function setUpFixture()
    {
        $this->money = new Money(50,"EUR");
    }

    public function testConstructeur()
    {
        $this->assertEquals($this->money->amount(),50);
        $this->assertEquals($this->money->currency(),"EUR");
    }

    //redondance de code
    public function testGetterAmount()
    {
        $this->assertEquals($this->money->amount(),50);
    }

    public function testGetterCurr()
    {
        $this->assertEquals($this->money->currency(),"EUR");
    }

    public function testAddMoney()
    {
        $m = new Money(100,"EUR");
        $this->money->addM($m);
        $this->assertEquals($this->money->amount(),150);
    }

    public function testAdd()
    {
        $this->money->add(200,"EUR");
        $this->assertEquals($this->money->amount(),250);
    }


}
