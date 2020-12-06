<?php

namespace App\Domain\Coin;

class CoinBox
{
    private array $racks = [];

    private function __construct(array $racks = [])
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

        $coinRack = CoinRack::fromValue($coin->value());
        $coinRack->addCoin($coin);

        $this->racks[] = $coinRack;
    }

    public function racks(): array
    {
        return $this->racks;
    }

    public static function fromRacks(array $racks = []): CoinBox
    {
        return new self($racks);
    }
}