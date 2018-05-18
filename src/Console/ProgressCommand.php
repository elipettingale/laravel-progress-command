<?php

namespace EliPett\ProgressCommand\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use EliPett\ProgressCommand\Services\ProgressBarFactory;

abstract class ProgressCommand extends Command
{
    private $items;

    /** @var ProgressBar */
    private $passedBar;
    /** @var ProgressBar */
    private $failedBar;

    abstract protected function getItems();
    abstract protected function fireItem($item): bool;

    public function handle()
    {
        $this->items = $this->getItems();

        $this->initialiseProgressBars();

        foreach ($this->items as $item) {
            $this->output->write("\033[1A");

            $result = $this->fireItem($item);

            if ($result === true) {
                $this->passedBar->advance();
            }

            print "\n";

            if ($result === false) {
                $this->failedBar->advance();
            }
        }

        print "\n";
    }

    private function initialiseProgressBars()
    {
        $count = \count($this->items);

        $this->passedBar = ProgressBarFactory::getPassedProgressBar($this->output, $count);
        $this->failedBar = ProgressBarFactory::getFailedProgressBar($this->output, $count);

        $this->passedBar->start();
        print "\n";
        $this->failedBar->start();
    }
}
