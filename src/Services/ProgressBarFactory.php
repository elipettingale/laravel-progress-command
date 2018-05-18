<?php

namespace EliPett\ProgressCommand\Services;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressBarFactory
{
    public static function getPassedProgressBar(OutputInterface $output, int $count)
    {
        $output->getFormatter()->setStyle('passed', new OutputFormatterStyle('green'));
        ProgressBar::setFormatDefinition('passed', '<passed>%current%/%max% [%bar%] %percent:3s%% passed</passed>');

        $passedBar = new ProgressBar($output, $count);
        $passedBar->setFormat('passed');

        return $passedBar;
    }

    public static function getFailedProgressBar(OutputInterface $output, int $count)
    {
        $output->getFormatter()->setStyle('failed', new OutputFormatterStyle('red'));
        ProgressBar::setFormatDefinition('failed', '<failed>%current%/%max% [%bar%] %percent:3s%% failed</failed>');

        $failedBar = new ProgressBar($output, $count);
        $failedBar->setFormat('failed');
        $failedBar->setProgressCharacter('#');

        return $failedBar;
    }
}
