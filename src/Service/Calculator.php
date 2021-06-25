<?php 

namespace App\Service;


class Calculator
{
    public function calculTVA(float $price){
        return $price*0.2;
    }

    public function calculTTC(float $price){
        $tva= $this->calculTVA($price);
        return $price+$tva;
    }
}
