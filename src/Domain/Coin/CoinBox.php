<?php

namespace App\Domain\Coin;

class CoinBox
{
    private array $racks = [];

    public function __construct(array $racks = [])
    {
        $this->racks = $racks;
    }

    public function addCoin(Coin $coin)
    {
        /** @var CoinRack $rack */
        foreach ($this->racks as $rack) {
            if ($rack->isSame($coin->value())) {
                $rack->addCoin($coin);
                return;
            }
        }

        $coinRack = new CoinRack($coin->value());
        $coinRack->addCoin($coin);

        $this->racks[] = $coinRack;
    }

    public function racks(): array
    {
        return $this->racks;
    }
}