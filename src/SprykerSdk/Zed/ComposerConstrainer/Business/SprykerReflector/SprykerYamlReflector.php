<?php

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

use Symfony\Component\Finder\SplFileInfo;

class SprykerYamlReflector
{
    protected $config;

    protected string $organisation = 'Spryker'; // TODO determin organisation properly
    protected string $packageName;
    protected string $moduleName;
    protected string $fileName;
    protected string $fileContent;

    public function __construct($config, SplFileInfo $splFileInfo)
    {
        $this->config = $config;

        $this->fileContent = $splFileInfo->getContents();
        $this->fileName = $splFileInfo->getFilename();

        $this->packageName = SprykerReflectionHelper::relativeFilePathToPackageName($this->organisation, $splFileInfo->getRelativePathname());
        [, $this->moduleName] = SprykerReflectionHelper::packageNameToNamespace($this->packageName);
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
}
