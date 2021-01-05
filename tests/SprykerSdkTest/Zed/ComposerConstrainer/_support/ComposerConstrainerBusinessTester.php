<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ComposerConstrainerBusinessTester extends Actor
{
    use _generated\ComposerConstrainerBusinessTesterActions;

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereModuleClassIsExtended(): string
    {
        $structure = [
            'src' => [
                'Project' => [
                    'Zed' => [
                        'Module' => [
                            'FooClass.php' => $this->buildFileContent('Spryker', 'FooClass'),
                        ],
                    ],
                ],
            ],
        ];

        $virtualDirectory = $this->getVirtualDirectory($structure);

        $this->includeUsedClass($virtualDirectory, 'Spryker', 'FooClass');

        require_once $virtualDirectory . 'src/Project/Zed/Module/FooClass.php';

        return $virtualDirectory;
    }

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereForeignModuleIsUsed(): string
    {
        $structure = [
            'src' => [
                'Project' => [
                    'Zed' => [
                        'Module' => [
                            'BarClass.php' => $this->buildFileContent('Foreign', 'BarClass', '\Foo\\'),
                        ],
                    ],
                ],
            ],
            'vendor' => [
                'foreign' => [
                    'bar' => [
                        'composer.json' => $this->buildPackageComposerJsonFile('foreign', 'bar')
                    ],
                ],
            ],
        ];

        $virtualDirectory = $this->getVirtualDirectory($structure);

        $this->includeUsedClass($virtualDirectory . 'vendor/foreign/bar/', 'Foreign', 'BarClass', '\Foo');

        require_once $virtualDirectory . 'src/Project/Zed/Module/BarClass.php';

        return $virtualDirectory;
    }

    /**
     * @param string $vendorName
     * @param string $namespace
     *
     * @return string
     */
    protected function buildPackageComposerJsonFile(string $vendorName, string $namespace): string
    {
        $fileContent = <<<CODE
{
  "name": "$vendorName/$namespace"
}

CODE;

        return $fileContent;
    }

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereModuleConfigIsExtended(): string
    {
        $structure = [
            'src' => [
                'Project' => [
                    'Zed' => [
                        'Module' => [
                            'ModuleConfig.php' => $this->buildFileContent('Spryker', 'ModuleConfig'),
                        ],
                    ],
                ],
            ],
        ];

        $virtualDirectory = $this->getVirtualDirectory($structure);

        $this->includeUsedClass($virtualDirectory, 'Spryker', 'ModuleConfig');

        require_once $virtualDirectory . 'src/Project/Zed/Module/ModuleConfig.php';

        return $virtualDirectory;
    }

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereModuleDependencyProviderIsExtended(): string
    {
        $structure = [
            'src' => [
                'Project' => [
                    'Zed' => [
                        'Module' => [
                            'ModuleDependencyProvider.php' => $this->buildFileContent('Spryker', 'ModuleDependencyProvider'),
                        ],
                    ],
                ],
            ],
        ];

        $virtualDirectory = $this->getVirtualDirectory($structure);

        $this->includeUsedClass($virtualDirectory, 'Spryker', 'ModuleDependencyProvider');

        require_once $virtualDirectory . 'src/Project/Zed/Module/ModuleDependencyProvider.php';

        return $virtualDirectory;
    }

    /**
     * @param string $path
     * @param string $organization
     * @param string $className
     * @param string $subNamespace
     *
     * @return void
     */
    protected function includeUsedClass(
        string $path,
        string $organization,
        string $className,
        string $subNamespace = '\Zed\Module'
    ): void {
        $fileContent = <<<CODE
<?php
namespace $organization$subNamespace;

class $className
{
}
CODE;
        $filePath = $path . $className . '.php';

        file_put_contents($filePath, $fileContent);

        require_once $filePath;
    }

    /**
     * @param string $organization
     * @param string $className
     * @param string $subNamespace
     *
     * @return string
     */
    protected function buildFileContent(
        string $organization,
        string $className,
        string $subNamespace = '\Zed\Module\\'
    ): string {
        $fileContent = <<<CODE
<?php
namespace Project\Zed\Module;

use $organization$subNamespace$className as $organization$className;

class $className extends $organization$className
{
}
CODE;

        return $fileContent;
    }
}
