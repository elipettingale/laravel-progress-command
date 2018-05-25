<?php

namespace EliPett\ProgressCommand\Services;

use EliPett\ProgressCommand\Structs\ProgressBarBlueprint;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class ProgressBarFactory
{
    public static function fromBlueprint(ProgressBarBlueprint $blueprint, OutputInterface $output, int $count)
    {
        $key = $blueprint->getKey();

        $style = new OutputFormatterStyle($blueprint->getForeground(), $blueprint->getBackground());
        $output->getFormatter()->setStyle($key, $style);
        ProgressBar::setFormatDefinition($key, "<$key>%current%/%max% [%bar%] %percent:3s%% {$blueprint->getName()}</$key>");

        $progressBar = new ProgressBar($output, $count);
        $progressBar->setFormat($key);
        $progressBar->setProgressCharacter($blueprint->getProgressCharacter());

        return $progressBar;
    }
}
