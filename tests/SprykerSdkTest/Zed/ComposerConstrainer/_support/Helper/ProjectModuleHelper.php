<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Symfony\Component\Filesystem\Filesystem;

class ProjectModuleHelper extends Module
{
    protected const TEST_DIRECTORY_SUB_PATH = 'testDirectorySubPath';

    /**
     * @var array
     */
    protected $config = [
        self::TEST_DIRECTORY_SUB_PATH => 'Fixtures/project/',
    ];

    /**
     * @var callable[]
     */
    protected $cleanupCallables = [];

    /**
     * @return string
     */
    protected function getFixturesDirectory(): string
    {
        return codecept_data_dir($this->config[static::TEST_DIRECTORY_SUB_PATH]);
    }

    /**
     * @param string $module
     * @param string $application
     *
     * @return void
     */
    public function haveExtendedModule(string $module, string $application): void
    {
        $pathToExtendedModule = $this->getPathToExtendedModule($module, $application);

        if (!is_dir($pathToExtendedModule)) {
            mkdir($pathToExtendedModule, 0777, true);
        }
    }

    /**
     * @param string $module
     * @param string $application
     *
     * @return string
     */
    protected function getPathToExtendedModule(string $module, string $application): string
    {
        return sprintf('%ssrc/Project/%s/%s/', $this->getFixturesDirectory(), $application, $module);
    }

    /**
     * @param string $organization
     * @param string $module
     * @param string $application
     *
     * @return void
     */
    public function haveDependencyProvider(string $organization, string $module, string $application): void
    {
        $this->haveExtendedModule($module, $application);

        $factoryContent = <<<CODE
<?php
namespace Project\\$application\\$module;

use $organization\\$application\\$module\\{$module}DependencyProvider as SprykerDependencyProvider;
use Generated\\Shared\\Transfer\\{$module}Transfer;
use Orm\\Zed\\{$module}\\Persistence\\Spy{$module}Query;

/**
 * @uses \\$organization\\$application\\$module\\{$module}DependencyProvider
 */
class {$module}DependencyProvider extends SprykerDependencyProvider
{
    /**
     * @param \\$organization\\$application\\$module\\{$module}DependencyProvider \$foo
     * @return \\$organization\\$application\\$module\\{$module}DependencyProvider
     */
    public function foo(\$foo){}
}
CODE;

        $pathToExtendedModule = $this->getPathToExtendedModule($module, $application);

        $filePath = $pathToExtendedModule . sprintf('%sDependencyProvider.php', $module);
        file_put_contents($filePath, $factoryContent);
    }

    /**
     * @param string $organization
     * @param string $module
     *
     * @return void
     */
    public function haveConfigFileWithUsedModule(string $organization, string $module): void
    {
        $configFilePath = $this->getFixturesDirectory() . 'config/config_default.php';
        $configContent = <<<CODE
<?php

use $organization\\Shared\\$module\\{$module}Constants;
CODE;

        if (!is_dir(dirname($configFilePath))) {
            mkdir(dirname($configFilePath), 0777, true);
        }
        file_put_contents($configFilePath, $configContent);
    }

    /**
     * @param callable $callable
     *
     * @return void
     */
    protected function addCleanup(callable $callable): void
    {
        $this->cleanupCallables[] = $callable;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->cleanup();
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        $this->cleanup();
    }

    /**
     * @return void
     */
    protected function cleanup(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getFixturesDirectory());
    }
}
