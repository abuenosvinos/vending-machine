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
        $rack = $this->getRack($coin);

        if ($rack == null) {
            $rack = CoinRack::fromValue($coin->value());
            $this->racks[] = $rack;
        }

        $rack->addCoin($coin);
    }

    public function setQuantityOfCoin(Coin $coin, int $quantity)
    {
        /** @var CoinRack $rack */
        foreach ($this->racks as &$rack) {
            if ($rack->isSame($coin)) {

                $rack = CoinRack::fromValue($coin->value());
                for ($i=0; $i<$quantity; $i++) {
                    $rack->addCoin($coin);
                }
                return;
            }
        }

        $coinRack = CoinRack::fromValue($coin->value());
        for ($i=0; $i<$quantity; $i++) {
            $coinRack->addCoin($coin);
        }

        $this->racks[] = $coinRack;
    }

    public function getRack(Coin $coin): ?CoinRack
    {
        foreach ($this->racks as $rack) {
            if ($rack->isSame($coin)) {
                return $rack;
            }
        }
        return null;
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