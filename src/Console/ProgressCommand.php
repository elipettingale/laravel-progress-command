<?php

namespace EliPett\ProgressCommand\Console;

use EliPett\ProgressCommand\Structs\ProgressBarBlueprint;
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
            $this->moveCursorUp();

            $result = $this->fireItem($item);

            if ($result === true) {
                $this->passedBar->advance();
            }

            $this->moveCursorDown();

            if ($result === false) {
                $this->failedBar->advance();
            }
        }

        $this->moveCursorDown();
    }

    private function initialiseProgressBars()
    {
        $count = \count($this->items);

        $this->passedBar = ProgressBarFactory::fromBlueprint(new ProgressBarBlueprint('passed', []), $this->output, $count);
        $this->failedBar = ProgressBarFactory::fromBlueprint(new ProgressBarBlueprint('failed', []), $this->output, $count);

        $this->passedBar->start();
        $this->moveCursorDown();
        $this->failedBar->start();
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
