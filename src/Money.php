<?php
/**
 * Created by PhpStorm.
 * User: gumonteilh
 * Date: 01/10/20
 * Time: 06:29
 */

namespace tp4;

class Money {

    private $amount ;
    private $curr ;
    public $conv;

    public function __construct($amount, $curr, $conv)
    {
        if($conv == null){
            throw new \InvalidArgumentException();
        }

        if(!$this->testCurrency($curr))
            throw new \InvalidArgumentException();

        if($amount< 0){
            $this->amount = 0;
        }else $this->amount = $amount;
        $this->curr = $curr;

        $this->conv = $conv;
    }

    public function amount ( ): float
    {
        return $this->amount;
    }

    public function currency ( ): string
    {
        return $this->curr;
    }

    public function addM (Money $m) : void
    {
        $this->add($m->amount,$m->curr);
    }

    public function add (int $namount , string $ncurrency ) : void
    {
        if($namount < 0)
            throw new \InvalidArgumentException();


        $this->amount += $this->conv->unit_Convertion( $this->curr . "-" . $ncurrency ) * $namount;

    }

    public function sub (int $namount , string $ncurrency ) : void
    {

        if($namount < 0){
            throw new \InvalidArgumentException();
        }
        $result = $this->amount - $this->conv->unit_Convertion( $this->curr . "-" . $ncurrency ) * $namount;

        if($result < 0){
            throw new \Exception();
        }

        $this->amount = $result;
    }

    public function testCurrency($curr): bool
    {
        if($curr == "EUR" || $curr == "USD")
            return true;
        return false;
    }

}