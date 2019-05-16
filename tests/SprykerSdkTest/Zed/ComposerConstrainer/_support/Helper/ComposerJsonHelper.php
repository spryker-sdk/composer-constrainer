<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class ComposerJsonHelper extends Module
{
    protected const TEST_DIRECTORY_SUB_PATH = 'testDirectorySubPath';

    /**
     * @var array
     */
    protected $config = [
        self::TEST_DIRECTORY_SUB_PATH => 'Fixtures/project/',
    ];

    /**
     * @return string
     */
    public function getComposerJsonPath(): string
    {
        $pathToComposerJson = codecept_data_dir($this->config[static::TEST_DIRECTORY_SUB_PATH]) . 'composer.json';

        return $pathToComposerJson;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function haveComposerJson(string $name = 'Project'): void
    {
        $composerJsonArray = [
            'name' => $name,
        ];
        $this->writeComposerJson($composerJsonArray);
    }

    /**
     * @param string $package
     * @param string $version
     *
     * @return void
     */
    public function haveComposerRequire(string $package, string $version): void
    {
        $composerJsonArray = $this->getComposerJsonArray();
        if (!isset($composerJsonArray['require'])) {
            $composerJsonArray['require'] = [];
        }

        $composerJsonArray['require'][$package] = $version;

        $this->writeComposerJson($composerJsonArray);
    }

    /**
     * @param string $package
     * @param string $version
     *
     * @return void
     */
    public function haveComposerRequireDev(string $package, string $version): void
    {
        $composerJsonArray = $this->getComposerJsonArray();
        if (!isset($composerJsonArray['require-dev'])) {
            $composerJsonArray['require-dev'] = [];
        }

        $composerJsonArray['require-dev'][$package] = $version;

        $this->writeComposerJson($composerJsonArray);
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    protected function getComposerJsonArray(): array
    {
        if (!file_exists($this->getComposerJsonPath())) {
            $this->haveComposerJson();
        }

        $decoded = json_decode(file_get_contents($this->getComposerJsonPath()), true);

        if ($decoded === null) {
            throw new Exception(json_last_error_msg());
        }

        return $decoded;
    }

    /**
     * @param array $composerJsonArray
     *
     * @return void
     */
    protected function writeComposerJson(array $composerJsonArray): void
    {
        if (!is_dir(dirname($this->getComposerJsonPath()))) {
            mkdir(dirname($this->getComposerJsonPath()), 0777, true);
        }

        file_put_contents($this->getComposerJsonPath(), json_encode($composerJsonArray));
    }

    /**
     * @param string $name
     * @param string $version
     *
     * @return void
     */
    public function assertComposerRequire(string $name, string $version): void
    {
        $composerJsonArray = $this->getComposerJsonArray();

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
        $composerJsonArray = $this->getComposerJsonArray();

        $this->assertArrayHasKey('require-dev', $composerJsonArray);
        $this->assertArrayHasKey($name, $composerJsonArray['require-dev']);
        $this->assertSame($version, $composerJsonArray['require-dev'][$name]);
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
        $filesystem->remove($this->getComposerJsonPath());
    }
}
