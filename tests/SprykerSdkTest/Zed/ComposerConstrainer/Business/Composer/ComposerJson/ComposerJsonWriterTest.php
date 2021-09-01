<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

use Codeception\Test\Unit;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriter;

/**
 * @group SprykerSdk
 * @group Zed
 * @group ComposerConstrainer
 * @group Business
 * @group ComposerJsonWriterTest
 */
class ComposerJsonWriterTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\ComposerConstrainer\ComposerConstrainerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWriteDefaultIndentation(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleDependencyProviderIsExtended();
        $composerJsonWriter = $this->getWriter($root);

        $array = [
            'name' => 'foo/bar',
            'require' => [
                'php' => '^7.4',
            ],
        ];

        // Act
        $result = $composerJsonWriter->write($array);

        // Assert
        $this->assertTrue($result);

        $path = 'composer.json';
        $content = $this->tester->getVirtualDirectoryFileContent($path);

        $path = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR;
        $file = $path . 'composer4space.json';
        $this->assertStringEqualsFile($file, $content);
    }

    /**
     * @return void
     */
    public function testWriteTwoSpaceIndentation(): void
    {
        // Arrange
        $path = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR;
        $file = $path . 'composer2space.json';

        $root = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        copy($file, $root . 'composer.json');
        $composerJsonWriter = $this->getWriter($root);

        $array = [
            'name' => 'foo/bar',
            'require' => [
                'php' => '^7.4',
            ],
        ];

        // Act
        $result = $composerJsonWriter->write($array);

        // Assert
        $this->assertTrue($result);

        $content = file_get_contents($root . 'composer.json');

        $this->assertStringEqualsFile($file, $content);
    }

    /**
     * @param string $root
     *
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriter
     */
    protected function getWriter(string $root): ComposerJsonWriter
    {
        $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getVendorDirectory', function () use ($root) {
            return $root . 'vendor/';
        });

        return new ComposerJsonWriter($composerConstrainerConfig);
    }
}
