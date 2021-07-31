<?php

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

use Symfony\Component\Finder\SplFileInfo;

class SprykerClassReflector extends \ReflectionClass
{
    protected $config;

    protected string $namespace; // NOT including class name
    protected string $moduleName;
    protected string $fileName;
    protected string $fileContent;
    protected string $className;

    protected ?array $usedCorePackageNames = null;

    public function __construct($config, SplFileInfo $splFileInfo)
    {
        $this->config = $config;

        $this->fileContent = $splFileInfo->getContents();
        preg_match_all('#\nnamespace +(\\w+\\\\\\w+\\\\(\\w+)[^;]*);#', $this->fileContent, $match);

        $this->moduleName = $match[2][0];
        $this->namespace = $match[1][0];

        preg_match_all('#\n(class|abstract class|interface|trait) +(\\w+)#', $this->fileContent, $match);
        $this->className = $match[2][0];

        $this->fileName = $splFileInfo->getFilename();

        parent::__construct($this->namespace . '\\' . $this->className);
    }

    /**
     * @return string[]
     */
    public function getUsedCorePackageNames(): array
    {
        if ($this->usedCorePackageNames !== null) {
            return $this->usedCorePackageNames;
        }

        $pattern = sprintf('#\nuse +(%s)\\\\\\w+\\\\(\\w+)\\\\#', implode('|', $this->config->getCoreNamespaces()));
        preg_match_all($pattern, $this->fileContent, $match);

        $this->usedCorePackageNames = [];
        foreach($match[1] as $key => $organisation) {
            $moduleName = $match[2][$key];

            $this->usedCorePackageNames[] = SprykerReflectionHelper::namespaceToPackageName($organisation, $moduleName);
        }

        $this->usedCorePackageNames = array_unique($this->usedCorePackageNames);

        return $this->usedCorePackageNames;
    }


}
