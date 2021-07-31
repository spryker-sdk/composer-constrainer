<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\SplFileInfo;

class SprykerYamlReflector
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    /**
     * @todo Determin organisation properly
     *
     * @var string
     */
    protected string $organisation = 'Spryker';

    /**
     * @var string
     */
    protected string $packageName;

    /**
     * @var string
     */
    protected string $moduleName;

    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var string
     */
    protected string $fileContent;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     */
    public function __construct(ComposerConstrainerConfig $config, SplFileInfo $splFileInfo)
    {
        $this->config = $config;

        $this->fileContent = $splFileInfo->getContents();
        $this->fileName = $splFileInfo->getFilename();

        $this->packageName = SprykerReflectionHelper::relativeFilePathToPackageName($this->organisation, $splFileInfo->getRelativePathname());
        [, $this->moduleName] = SprykerReflectionHelper::packageNameToNamespace($this->packageName);
    }

    /**
     * @return string
     */
    public function getPackageName(): string
    {
        return $this->packageName;
    }

    /**
     * @return string
     */
    public function getOrganisation(): string
    {
        return $this->organisation;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }
}
