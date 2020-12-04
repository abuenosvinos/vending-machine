<?php

namespace App\UI\Command\Operation;

interface Operation
{
    public function command() : string;

    public function actions() : array;

    public function attend(string $input) : bool;

    public function params(string $input) : array;
}
