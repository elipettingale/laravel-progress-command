<?php

namespace EliPett\ProgressCommand\Structs;

class ProgressBarBlueprint
{
    private $key;
    private $description;

    private $foreground;
    private $background;
    private $progressCharacter;

    public function __construct(string $key, string $description, array $data)
    {
        $this->key = $key;
        $this->description = $description;

        $this->foreground = array_get($data, 'default');
        $this->background = array_get($data, 'default');
        $this->progressCharacter = array_get($data, 'progress-character');
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getDescription(): string
    {
        return $this->description;
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
