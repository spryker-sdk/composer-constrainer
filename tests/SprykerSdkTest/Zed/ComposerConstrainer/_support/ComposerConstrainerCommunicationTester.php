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
class ComposerConstrainerCommunicationTester extends Actor
{
    use _generated\ComposerConstrainerCommunicationTesterActions;

    /**
     * @param string $name
     * @param string $version
     *
     * @return void
     */
    public function assertComposerRequire(string $name, string $version): void
    {
        $composerJsonArray = json_decode(file_get_contents($this->getVirtualDirectory() . 'composer.json'), true);

        $this->assertArrayHasKey('require', $composerJsonArray);
        $this->assertArrayHasKey($name, $composerJsonArray['require']);
        $this->assertSame($version, $composerJsonArray['require'][$name]);
    }

    /**
     * @param string $name
     * @param string $version
     *
     * @return void
     */
    public function assertComposerRequireDev(string $name, string $version): void
    {
        $composerJsonArray = json_decode(file_get_contents($this->getVirtualDirectory() . 'composer.json'), true);

        $this->assertArrayHasKey('require-dev', $composerJsonArray);
        $this->assertArrayHasKey($name, $composerJsonArray['require-dev']);
        $this->assertSame($version, $composerJsonArray['require-dev'][$name]);
    }

    /**
     * @param string $package
     * @param string $version
     * @param string $section
     *
     * @return void
     */
    public function haveComposerJsonAndOverriddenClass(string $package, string $version, string $section = 'require'): void
    {
        $composerJsonArray = [
            'name' => 'project',
            $section => [
                $package => $version,
            ],
        ];

        $virtualDirectory = $this->getVirtualDirectory($this->getStructure($composerJsonArray));

        $this->includeUsedClass($virtualDirectory, 'Spryker', 'FooClass');

        require_once $virtualDirectory . 'src/Project/Zed/Module/FooClass.php';

        $this->mockConfigMethod('getProjectRootPath', $virtualDirectory);
    }

    /**
     * @param string $package
     * @param string $version
     * @param string $section
     *
     * @return void
     */
    public function haveComposerLockAndOverriddenClass(string $package, string $version, string $section = 'require'): void
    {
        $composerJsonArray = [
            'name' => 'project',
            $section => [],
        ];

        $composerLockArray = [
            ($section === 'require') ? 'packages' : 'packages-dev' => [
                [
                    'name' => $package,
                    'version' => ltrim($version, '^~'),
                ],
            ],
        ];

        $virtualDirectory = $this->getVirtualDirectory($this->getStructure($composerJsonArray, $composerLockArray));

        $this->includeUsedClass($virtualDirectory, 'Spryker', 'FooClass');

        require_once $virtualDirectory . 'src/Project/Zed/Module/FooClass.php';

        $this->mockConfigMethod('getProjectRootPath', $virtualDirectory);
    }

    /**
     * @return void
     */
    protected function includeComposerLock()
    {
    }

    /**
     * @return void
     */
    public function haveOverriddenClass(): void
    {
        $composerJsonArray = [
            'name' => 'project',
        ];

        $virtualDirectory = $this->getVirtualDirectory($this->getStructure($composerJsonArray));

        $this->includeUsedClass($virtualDirectory, 'Spryker', 'FooClass');

        require_once $virtualDirectory . 'src/Project/Zed/Module/FooClass.php';

        $this->mockConfigMethod('getProjectRootPath', $virtualDirectory);
    }

    /**
     * @param array $composerJsonArray
     * @param array $composerLockAsArray
     *
     * @return array
     */
    protected function getStructure(array $composerJsonArray, array $composerLockAsArray = []): array
    {
        return [
            'composer.json' => json_encode($composerJsonArray),
            'composer.lock' => json_encode($composerLockAsArray),
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
    }

    /**
     * @param string $package
     * @param string $version
     * @param string $section
     *
     * @return void
     */
    public function haveComposerJson(string $package, string $version, string $section = 'require'): void
    {
        $composerJsonArray = [
            'name' => 'project',
            $section => [
                $package => $version,
            ],
        ];
        $composerLockArray = [
            $section => [
                [
                    'name' => $package,
                    'version' => $version,
                ],
            ],
        ];

        $structure = [
            'composer.json' => json_encode($composerJsonArray),
            'composer.lock' => json_encode($composerLockArray),
        ];

        $virtualDirectory = $this->getVirtualDirectory($structure);

        $this->mockConfigMethod('getProjectRootPath', $virtualDirectory);
    }

    /**
     * @param string $root
     * @param string $organization
     * @param string $className
     *
     * @return void
     */
    protected function includeUsedClass(string $root, string $organization, string $className): void
    {
        $fileContent = <<<CODE
<?php
namespace $organization\Zed\Module;

class $className
{
}
CODE;
        $filePath = $root . $className . '.php';

        file_put_contents($filePath, $fileContent);

        require_once $filePath;
    }

    /**
     * @param string $organization
     * @param string $className
     *
     * @return string
     */
    protected function buildFileContent(string $organization, string $className): string
    {
        $fileContent = <<<CODE
<?php
namespace Project\Zed\Module;

use $organization\Zed\Module\\$className as $organization$className;

class $className extends $organization$className
{
}
CODE;

        return $fileContent;
    }
}
