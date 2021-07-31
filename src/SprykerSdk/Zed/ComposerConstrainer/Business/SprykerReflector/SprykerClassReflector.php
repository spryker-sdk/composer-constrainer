<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

use ReflectionClass;
use ReflectionMethod;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\SplFileInfo;

class SprykerClassReflector
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected ComposerConstrainerConfig $config;

    /**
     * @var string[]
     */
    protected array $publicApiClassSuffixes = [
        'Service\.php',
        'Client\.php',
        'Facade\.php',
        'QueryContainer\.php',
        'Controller\.php',
        'Config\.php',
        'DependencyProvider\.php',
    ];

    /**
     * @var string[]
     */
    protected array $publicApiInterfaceSuffixes = [
        'ServiceInterface\.php',
        'ClientInterface\.php',
        'FacadeInterface\.php',
        'QueryContainerInterface\.php',
        'PluginInterface\.php',
    ];

    /**
     * @var string[]
     */
    protected array $configurationClassSuffixes = [
        'Config\.php',
        'DependencyProvider\.php',
    ];

    /**
     * @var bool
     */
    protected bool $isPublicApi;

    /**
     * @var bool
     */
    protected bool $isConfiguration;

    /**
     * @var bool
     */
    protected bool $isFactory;

    /**
     * @var bool
     */
    protected bool $isInterface;

    /**
     * @var string
     */
    protected string $namespace;

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
     * @var string
     */
    protected string $className;

    /**
     * @var \ReflectionClass
     */
    protected \ReflectionClass $reflectionClass;

    /**
     * @var bool
     */
    protected bool $isParentCore;

    /**
     * @var string
     */
    protected string $parentNamespace;

    /**
     * @var string
     */
    protected string $parentOrganisation;

    /**
     * @var string
     */
    protected string $parentModuleName;

    /**
     * @var string
     */
    protected string $parentClassName;

    /**
     * @var string
     */
    protected string $parentPackageName;

    /**
     * @var array|null
     */
    protected ?array $usedCorePackageNames = null;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     */
    public function __construct(ComposerConstrainerConfig $config, SplFileInfo $splFileInfo)
    {
        $this->config = $config;

        $this->fileContent = $splFileInfo->getContents();
        preg_match_all('#\nnamespace +(\\w+\\\\\\w+\\\\(\\w+)[^;]*);#', $this->fileContent, $match);

        $this->moduleName = $match[2][0];
        $this->namespace = $match[1][0];

        preg_match_all('#\n(class|abstract class|interface|trait) +(\\w+)#', $this->fileContent, $match);
        $this->className = $match[2][0];
        $this->fileName = $splFileInfo->getFilename();

        $this->isPublicApi = preg_match('#(' . implode('|', array_merge($this->publicApiClassSuffixes, $this->publicApiInterfaceSuffixes)) . ')#', $this->fileName);
        $this->isConfiguration = preg_match('#(' . implode('|', $this->configurationClassSuffixes) . ')#', $this->fileName);
        $this->isFactory = preg_match('#Factory\.php#', $this->fileName);

        $this->reflectionClass = new ReflectionClass($this->namespace . '\\' . $this->className);
        $this->isInterface = $this->reflectionClass->isInterface();

        $this->parentClassName = $this->reflectionClass->getParentClass() ? $this->reflectionClass->getParentClass()->getShortName() : "";
        $this->parentNamespace = $this->reflectionClass->getParentClass() ? $this->reflectionClass->getParentClass()->getNamespaceName() : "";
        preg_match_all('#(\\w+)\\\\\\w+\\\\(\\w+)#', $this->parentNamespace, $match);
        $this->parentOrganisation = count($match[1]) > 0 ? $match[1][0] : "";
        $this->parentModuleName = count($match[2]) > 0 ? $match[2][0] : "";
        $this->parentPackageName = $this->parentOrganisation === "" ? "" : SprykerReflectionHelper::namespaceToPackageName($this->parentOrganisation, $this->parentModuleName);
        $this->isParentCore = $this->parentModuleName && in_array($this->parentOrganisation, $this->config->getCoreNamespaces(), true);
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
        foreach ($match[1] as $key => $organisation) {
            $moduleName = $match[2][$key];

            $this->usedCorePackageNames[] = SprykerReflectionHelper::namespaceToPackageName($organisation, $moduleName);
        }

        $this->usedCorePackageNames = array_unique($this->usedCorePackageNames);

        return $this->usedCorePackageNames;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return \ReflectionMethod[]
     */
    public function getNewMethods(): array
    {
        return array_filter($this->reflectionClass->getMethods(), function (ReflectionMethod $method): bool {
            return $method->getDeclaringClass()->getNamespaceName() === $this->reflectionClass->getNamespaceName();
        });
    }

    /**
     * @return \ReflectionMethod[]
     */
    public function getParentMethods(): array
    {
        $parentClass = $this->reflectionClass->getParentClass();
        if ($parentClass === false) {
            return [];
        }

        return $this->reflectionClass->getParentClass()->getMethods();
    }

    /**
     * @return bool
     */
    public function getIsParentCore(): bool
    {
        return $this->isParentCore;
    }

    /**
     * @return bool
     */
    public function getIsConfiguration(): bool
    {
        return $this->isConfiguration;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return bool
     */
    public function getIsPublicApi(): bool
    {
        return $this->isPublicApi;
    }

    /**
     * @return bool
     */
    public function getIsInterface(): bool
    {
        return $this->isInterface;
    }

    /**
     * @return bool
     */
    public function getIsFactory(): bool
    {
        return $this->isFactory;
    }

    /**
     * @return string
     */
    public function getParentPackageName(): string
    {
        return $this->parentPackageName;
    }

    /**
     * @return string
     */
    public function getParentOrganisation(): string
    {
        return $this->parentOrganisation;
    }

    /**
     * @return string
     */
    public function getParentModuleName(): string
    {
        return $this->parentModuleName;
    }
}
