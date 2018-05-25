<?php

namespace EliPett\ProgressCommand\Structs;

class ProgressBarBlueprint
{
    private $name;

    private $foreground;
    private $background;
    private $progressCharacter;

    public function __construct(string $name, array $data)
    {
        $this->name = $name;

        $this->foreground = array_get($data, 'foreground');
        $this->background = array_get($data, 'background');
        $this->progressCharacter = array_get($data, 'progress-character');
    }

    public function getKey(): string
    {
        return lower_hyphen_case($this->name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getForeground(): string
    {
        return $this->foreground ?? 'white';
    }

    public function getBackground(): string
    {
        return $this->background ?? 'black';
    }

    public function getProgressCharacter(): string
    {
        return $this->progressCharacter ?? '>';
    }
}
