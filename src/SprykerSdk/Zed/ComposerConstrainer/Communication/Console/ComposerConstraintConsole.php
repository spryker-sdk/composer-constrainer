<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Communication\Console;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
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
    public const OPTION_STRICT_RUN = 'strict';
    public const OPTION_STRICT_RUN_SHORT = 's';
    public const OPTION_VERBOSE_RUN = 'verbose';
    public const OPTION_OUTPUT_FORMAT = 'output-format';
    public const OPTION_OUTPUT_FORMAT_SHORT = 'o';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Updates composer constraints in projects. When a module is extended on project level, this command will change ^ to ~ in the project\'s composer.json. This will make sure that a composer update will only pull patch versions of it for better backwards compatibility.');

        $this->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Use this option to validate your projects\' constraints.');
        $this->addOption(static::OPTION_STRICT_RUN, static::OPTION_STRICT_RUN_SHORT, InputOption::VALUE_NONE, 'Use this option to validate your projects\' constraints using strict manner.');
        $this->addOption(static::OPTION_OUTPUT_FORMAT, static::OPTION_OUTPUT_FORMAT_SHORT, InputOption::VALUE_OPTIONAL, 'Use this option with "csv" value in strict validation to print report in comma separated, import ready format.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption(static::OPTION_STRICT_RUN)) {
            return $this->runStrictMode(
                (bool)$input->getOption(static::OPTION_DRY_RUN),
                (bool)$input->getOption(static::OPTION_VERBOSE_RUN),
                $input->getOption(static::OPTION_OUTPUT_FORMAT),
            );
        }

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
        $composerConstraintCollectionTransfer = $this->getFacade()->validateConstraints();

        if ($composerConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            $this->output->writeln('<fg=green>No constraint issues found.</>');

            return static::CODE_SUCCESS;
        }

        $this->outputValidationFindings($composerConstraintCollectionTransfer);

        $this->output->writeln(sprintf('<fg=magenta>%s fixable constraint issues found.</>', $composerConstraintCollectionTransfer->getComposerConstraints()->count()));

        return static::CODE_ERROR;
    }

    /**
     * @param bool $isDryRun
     * @param bool $isVerbose
     * @param string|null $format
     *
     * @return int
     */
    protected function runStrictMode(bool $isDryRun, bool $isVerbose, ?string $format = null): int
    {
        if ($isDryRun) {
            $composerConstraintCollectionTransfer = $this->getFacade()->validateConstraints(true);
        } else {
            $composerConstraintCollectionTransfer = $this->getFacade()->updateConstraints(true);
        }

        if ($composerConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            $this->output->writeln('<fg=green>No constraint issues found.</>');

            return static::CODE_SUCCESS;
        }

        $this->outputStrictValidationFindings($composerConstraintCollectionTransfer, $isVerbose, $format);

        $this->output->writeln(sprintf('<fg=magenta>%s constraint issues found.</>', $composerConstraintCollectionTransfer->getComposerConstraints()->count()));

        return static::CODE_ERROR;
    }

    /**
     * @param ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     * @param bool $isVerbose
     * @param string|null $format
     *
     * @return void
     */
    protected function outputStrictValidationFindings(ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer, bool $isVerbose, ?string $format = null): void
    {
        $lineStructure = '%-70s | %10s | %10s | %10s | %13s | %13s | %10s | %s';
        if ($format === "csv") {
            $lineStructure = preg_replace('/\|/', ',', $lineStructure);
            $lineStructure = preg_replace('/[0-9 \-]/', '', $lineStructure);
        }

        $this->output->writeln(
            sprintf(
                $lineStructure,
                'Module',
                'Customized',
                'Configured',
                'Line count',
                'Expected',
                'Actual',
                'Locked',
                'Reasons'
            )
        );

        foreach ($composerConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $reasons = $isVerbose ? '{"reasons:": ["' . implode('","', array_unique($composerConstraintTransfer->getModuleInfo()->getConstraintReasons())) . '"]}' : '';
            $reasons = $format === "csv" ? '"' . str_replace('"', '""', $reasons) . '"' : $reasons;

            $this->output->writeln(
                sprintf(
                    $lineStructure,
                    $composerConstraintTransfer->getName(),
                    $composerConstraintTransfer->getModuleInfo()->getIsCustomized() ? 'Yes' : '',
                    $composerConstraintTransfer->getModuleInfo()->getIsConfigured() ? 'Yes' : '',
                    $composerConstraintTransfer->getModuleInfo()->getCustomizedLineCount() ?: 0,
                    $composerConstraintTransfer->getModuleInfo()->getExpectedConstraintLock() . $composerConstraintTransfer->getModuleInfo()->getExpectedVersion(),
                    $composerConstraintTransfer->getModuleInfo()->getDefinedConstraintLock() . $composerConstraintTransfer->getModuleInfo()->getDefinedVersion(),
                    $composerConstraintTransfer->getModuleInfo()->getLockedVersion(),
                    $reasons
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     *
     * @return void
     */
    protected function outputValidationFindings(ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer): void
    {
        foreach ($composerConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $this->output->writeln(sprintf('<fg=yellow>%s</> appears to be extended on project level.', $composerConstraintTransfer->getName()));
            if (!$this->output->isVerbose()) {
                continue;
            }
            foreach ($composerConstraintTransfer->getMessages() as $messageTransfer) {
                $this->output->writeln('- ' . $messageTransfer->getMessage());
            }
        }
    }

    /**
     * @return int
     */
    protected function runUpdate(): int
    {
        $composerConstraintCollectionTransfer = $this->getFacade()->updateConstraints();

        if ($composerConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            $this->output->writeln('<fg=green>No constraint issues found.</>');
        } else {
            $this->output->writeln(sprintf('<fg=green>%s constraint issues found and fixed.</>', $composerConstraintCollectionTransfer->getComposerConstraints()->count()));
        }

        return static::CODE_SUCCESS;
    }
}
