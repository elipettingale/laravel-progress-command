<?php

namespace EliPett\ProgressCommand\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class ProgressCommand extends Command
{
    protected $name = 'core:progress-command';
    protected $description = 'Test progress command';

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
        $this->setOutputStyles();
        $this->setProgressBars();
        $this->startProgressBars();
    }

    private function setOutputStyles()
    {
        $formatter = $this->output->getFormatter();

        $formatter->setStyle('passed', new OutputFormatterStyle('green'));
        ProgressBar::setFormatDefinition('passed', '<passed>%current%/%max% [%bar%] %percent:3s%% passed</passed>');

        $formatter->setStyle('failed', new OutputFormatterStyle('red'));
        ProgressBar::setFormatDefinition('failed', '<failed>%current%/%max% [%bar%] %percent:3s%% failed</failed>');
    }

    private function setProgressBars()
    {
        $this->passedBar = new ProgressBar($this->output, \count($this->items));
        $this->passedBar->setFormat('passed');

        $this->failedBar = new ProgressBar($this->output, \count($this->items));
        $this->failedBar->setFormat('failed');
        $this->failedBar->setProgressCharacter('#');
    }

    private function startProgressBars()
    {
        $this->passedBar->start();
        print "\n";
        $this->failedBar->start();
    }
}
