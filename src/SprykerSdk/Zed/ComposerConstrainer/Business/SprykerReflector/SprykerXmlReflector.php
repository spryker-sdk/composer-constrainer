<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\SplFileInfo;

class SprykerXmlReflector
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
    protected $organisation = 'Spryker';

    /**
     * @var string
     */
    protected $packageName;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $fileContent;

    /**
     * @var bool
     */
    protected $isTransfer;

    /**
     * @var bool
     */
    protected $isSchema;

    /**
     * @var bool
     */
    protected $isNavigation;

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

        $fileName = $splFileInfo->getFilename();
        $this->isTransfer = (bool)preg_match('/transfer\.xml/', $fileName);
        $this->isNavigation = (bool)preg_match('/navigation\.xml/', $fileName);
        $this->isSchema = (bool)preg_match('/schema\.xml/', $fileName);
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

    /**
     * @return bool
     */
    public function getIsTransfer(): bool
    {
        return $this->isTransfer;
    }

    /**
     * @return bool
     */
    public function getIsNavigation(): bool
    {
        return $this->isNavigation;
    }

    /**
     * @return bool
     */
    public function getIsSchema(): bool
    {
        return $this->isSchema;
    }
}
