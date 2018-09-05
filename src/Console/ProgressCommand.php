<?php

namespace EliPett\ProgressCommand\Console;

use Illuminate\Console\Command;
use EliPett\ProgressCommand\Services\ProgressBarFactory;

abstract class ProgressCommand extends Command
{
    private $items;
    private $progressBars;

    abstract protected function getItems();
    abstract protected function fireItem($item): string;
    abstract protected function getProgressBarBlueprints(): array;

    public function handle()
    {
        $this->items = $this->getItems();
        $this->initialiseProgressBars();
        $start_time = microtime(true);

        foreach ($this->items as $item) {
            $this->moveCursorToTop();
            $this->renderInfoBar($item);

            $result = $this->fireItem($item);

            foreach ($this->progressBars as $key => $progressBar) {
                if ($result === $key) {
                    $progressBar->advance();
                }
                $this->moveCursorDown();
            }
        }

        $end_time = microtime(true);
        $this->renderElapsedTimeFormat($start_time, $end_time);
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
    }

    private function renderInfoBar($item)
    {
        if ($this->hasInfoBar()) {
            $this->info('Current Item: ' . $this->getItemIdentifier($item));
        }
    }

    private function moveCursorToTop()
    {
        $count = \count($this->progressBars);

        if ($this->hasInfoBar()) {
            $count++;
        }

        $this->moveCursorUp($count);
    }

    private function moveCursorUp(int $lines = 1)
    {
        for ($i = 0; $i < $lines; ++$i) {
            $this->output->write("\033[1A");
        }
    }

    private function moveCursorDown(int $lines = 1)
    {
        for ($i = 0; $i < $lines; ++$i) {
            print "\n";
        }
    }

    private function hasInfoBar(): bool
    {
        return method_exists($this, 'getItemIdentifier');
    }

    private function renderElapsedTimeFormat($start_time, $end_time)
    {
        $microtime = convert_microtime($end_time - $start_time);

        $this->info($microtime->toString());
    }
}
