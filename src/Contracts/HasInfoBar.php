<?php

namespace EliPett\ProgressCommand\Contracts;

interface HasInfoBar
{
    public function getItemIdentifier($item): string;
}
