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
        $hours = (int)
            ($minutes = (int)
                ($seconds = (int)
                    ($milliseconds = (int)
                    (($end_time - $start_time) * 1000))
                    / 1000)
                / 60)
            / 60;

        $minutes = $minutes % 60;
        $seconds = $seconds % 60;
        $milliseconds = $milliseconds % 1000;

        if ($hours !== 0) {
            $this->info("Elapsed Time: {$hours} hours {$minutes} minutes {$seconds} seconds {$milliseconds} milliseconds");
            return;
        }

        if ($minutes !== 0) {
            $this->info("Elapsed Time: {$minutes} minutes {$seconds} seconds {$milliseconds} milliseconds");
            return;
        }

        if ($seconds !== 0) {
            $this->info("Elapsed Time: {$seconds} seconds {$milliseconds} milliseconds");
            return;
        }

        $this->info("Elapsed Time: {$milliseconds} milliseconds");
    }
}
