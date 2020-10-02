<?php
/**
 * Created by PhpStorm.
 * User: gumonteilh
 * Date: 01/10/20
 * Time: 06:32
 */



namespace tp4;

use PHPUnit\Framework\TestCase;

require_once('src/Money.php');
require_once('src/Convertion.php');

class MoneyTest extends TestCase
{
    protected $money;
    protected $obs;

    /**
     * @before
     */
    public function setUpFixture()
    {
        $this->obs = $this->createMock(Convertion::class);

        $map = [
            ['EUR-EUR',1],
            ['EUR-USD',0.84],
            ['USD-EUR',1.19],
            ['USD-USD',1],
        ];

        $this->obs ->method('unit_Convertion')
            ->will($this->returnValueMap($map));

        $this->money = new Money(50,"EUR",$this->obs );
    }


    /*
     * test du constructeur
     */

    public function testConstructeur()
    {

        $m = new Money(50,"EUR",new Convertion());

        $this->assertEquals($m->amount(),50);
        $this->assertEquals($m->currency(),"EUR");
    }

    public function testConstructeurWrongAmount() //si valeur negative alors amount = 0
    {
        $m = new Money(-10,"EUR",new Convertion());

        $this->assertEquals($m->amount(),0);
    }

    public function testConstructeurWrongCurrency()
    {
        $this->expectException("\InvalidArgumentException");
        new Money(-10,"wrong",new Convertion());

    }

    public function testConstructeurConverterNull()
    {
        $this->expectException("\InvalidArgumentException");
        new Money(-10,"wrong",null);

    }

    /*
     * test des getter
     */

    public function testGetterAmount()
    {
        $this->assertEquals($this->money->amount(),50);
    }

    public function testGetterCurr()
    {
        $this->assertEquals($this->money->currency(),"EUR");
    }


    /*
     * test de AddMoney()
     */

    public function testAddMoneySameCurrency()
    {
        $m = new Money(100,"EUR",new Convertion());

        $this->money->addM($m);
        $this->assertEquals($this->money->amount(),150);
    }

    public function testAddMoneyNotSameCurrency()
    {
        $this->obs->expects($this->once())
            ->method('unit_Convertion');
        
        
        $m = new Money(100,"USD",new Convertion());
        $this->money->addM($m);
        $this->assertEquals($this->money->amount(),134);
    }

    public function testAddMoneyWrongCurrency()
    {
        $mock = $this->createMock(Convertion::class);

        $mock ->method('unit_Convertion')
            ->with($this->equalTo($this->money->currency().'-'.'wrong'))
            ->will($this->throwException(new \InvalidArgumentException));

        $this->expectException("\InvalidArgumentException");

        $m2 = new Money(100,"wrong",new Convertion());

        $m = new Money(50,"EUR",$mock);
        $m->addM($m2);
    }


    /*
     * test de Add()
     */

    public function testAddSameCurrency()
    {
        $this->obs->expects($this->once())
            ->method('unit_Convertion');

        $this->money->add(200,"EUR");
        $this->assertEquals($this->money->amount(),250);
    }

    public function testAddNotSameCurrency()
    {
        $this->obs->expects($this->once())
            ->method('unit_Convertion');

        $this->money->add(100,"USD");
        $this->assertEquals($this->money->amount(),134);
    }

    public function testAddWrongCurrency(){

        $mock = $this->createMock(Convertion::class);

        $mock ->method('unit_Convertion')
            ->with($this->equalTo($this->money->currency().'-'.'wrong'))
            ->will($this->throwException(new \InvalidArgumentException));

        $this->expectException("\InvalidArgumentException");

        $m = new Money(50,"EUR",$mock);
        $m->add(200,"wrong");

    }

    public function testAddWrongAmount()
    {
        $this->expectException("\InvalidArgumentException");

        $this->money->add(-1,"EUR");
    }


    /*
     * test de Sub()
     */

    public function testSubSameCurrency()
    {
        $this->obs->expects($this->once())
            ->method('unit_Convertion');

        $this->money->sub(20,"EUR");
        $this->assertEquals($this->money->amount(),30);
    }

    public function testSubNotSameCurrency()
    {

        $this->money->sub(10,"USD");
        $this->assertEquals($this->money->amount(),41.6);
    }

    public function testSubWrongCurrency(){

        $mock = $this->createMock(Convertion::class);

        $mock ->method('unit_Convertion')
            ->with($this->equalTo($this->money->currency().'-'.'wrong'))
            ->will($this->throwException(new \InvalidArgumentException));

        $this->expectException("\InvalidArgumentException");

        $m = new Money(50,"EUR",$mock);
        $m->sub(15,"wrong");

    }

    public function testSubWrongAmount()
    {
        $this->expectException("\InvalidArgumentException");

        $this->money->sub(-1,"EUR");
    }

    public function testSubResultBellowZero()
    {
        $this->expectException("\Exception");

        $this->money->sub(100,"EUR");
    }


    /*
     * test de testCurrency()
     */

    public function currencyProvider()
    {
        return [
            ["EUR", true],
            ["USD", true],
            ["eur", false],
            ["usd", false],
            ["fezfzeze", false],
            ["", false],
            [null, false],
        ];
    }

    /**
     * @dataProvider currencyProvider
     */
    public function testCurrencyGood($curr,$expected)
    {
        $this->assertEquals($expected, $this->money->testCurrency($curr));
    }


}
