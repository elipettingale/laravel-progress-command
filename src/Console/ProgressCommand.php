<?php

namespace EliPett\ProgressCommand\Console;

use Illuminate\Console\Command;
use EliPett\ProgressCommand\Services\ProgressBarFactory;

abstract class ProgressCommand extends Command
{
    private $items;
    private $progressBars;

    abstract protected function getItems();
    abstract protected function fireItem($item): bool;
    abstract protected function getProgressBarBlueprints(): array;

    public function handle()
    {
        $this->items = $this->getItems();

        $this->initialiseProgressBars();

        foreach ($this->items as $item) {
            $this->moveCursorUp();

            $result = $this->fireItem($item);

            foreach ($this->progressBars as $key => $progressBar) {
                if ($result === $key) {
                    $progressBar->advance();
                }
                $this->moveCursorDown();
            }
        }

        $this->moveCursorDown();
    }

    private function initialiseProgressBars()
    {
        $count = \count($this->items);

        foreach ($this->getProgressBarBlueprints() as $blueprint) {
            $this->progressBars[$blueprint->getKey()] = ProgressBarFactory::fromBlueprint($blueprint, $this->output, $count);
        }

        foreach ($this->progressBars as $progressBar) {
            $progressBar->start();
            $this->moveCursorDown();
        }

        $this->moveCursorUp();
    }

    private function moveCursorUp()
    {
        $this->output->write("\033[1A");
    }

    private function moveCursorDown()
    {
        print "\n";
    }
}
