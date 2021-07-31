<?php

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

use Symfony\Component\Finder\SplFileInfo;

class SprykerXmlReflector
{
    protected $config;

    protected string $organisation = 'Spryker'; // TODO determin organisation properly
    protected string $packageName;
    protected string $moduleName;
    protected string $fileName;
    protected string $fileContent;

    protected bool $isTransfer;
    protected bool $isSchema;
    protected bool $isNavigation;

    public function __construct($config, SplFileInfo $splFileInfo)
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

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getOrganisation(): string
    {
        return $this->organisation;
    }

    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    public function getIsTransfer(): bool
    {
        return $this->isTransfer;
    }

    public function getIsNavigation(): bool
    {
        return $this->isNavigation;
    }

    public function getIsSchema(): bool
    {
        return $this->isSchema;
    }
}
