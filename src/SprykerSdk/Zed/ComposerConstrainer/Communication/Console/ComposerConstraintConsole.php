<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Communication\Console;

use Generated\Shared\Transfer\ConstraintValidationResultTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Zed\ComposerConstrainer\Business\ComposerConstrainerFacadeInterface getFacade()
 */
class ComposerConstraintConsole extends Console
{
    public const COMMAND_NAME = 'code:constraint:modules';
    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_DRY_RUN_SHORT = 'd';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Updates composer constraint in projects.');

        $this->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_OPTIONAL, 'Use this option to validate your projects constraints.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption(static::OPTION_DRY_RUN)) {
            return $this->runValidation();
        }

        return $this->runUpdate();
    }

    /**
     * @return int
     */
    protected function runValidation(): int
    {
        $constraintValidationResultTransfer = $this->getFacade()->validateConstraints();

        if ($constraintValidationResultTransfer->getIsSuccessful()) {
            return static::CODE_SUCCESS;
        }

        if ($this->output->isVeryVerbose()) {
            $this->outputValidationFindings($constraintValidationResultTransfer);
        }

        return static::CODE_ERROR;
    }

    /**
     * @param \Generated\Shared\Transfer\ConstraintValidationResultTransfer $constraintValidationResultTransfer
     *
     * @return void
     */
    protected function outputValidationFindings(ConstraintValidationResultTransfer $constraintValidationResultTransfer): void
    {
        foreach ($constraintValidationResultTransfer->getInvalidConstraints() as $invalidConstraintTransfer) {
            $this->output->writeln(sprintf('<fg=green>%s</> seems to have an invalid constraint.', $invalidConstraintTransfer->getName()));
            foreach ($invalidConstraintTransfer->getMessages() as $messageTransfer) {
                $this->output->writeln($messageTransfer->getMessage());
            }
        }
    }

    /**
     * @return int
     */
    protected function runUpdate(): int
    {
        $this->getFacade()->updateConstraints();

        return static::CODE_SUCCESS;
    }
}
