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
    protected $config;

    /**
     * @var string[]
     */
    protected $publicApiClassSuffixes = [
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
    protected $publicApiInterfaceSuffixes = [
        'ServiceInterface\.php',
        'ClientInterface\.php',
        'FacadeInterface\.php',
        'QueryContainerInterface\.php',
        'PluginInterface\.php',
    ];

    /**
     * @var string[]
     */
    protected $configurationClassSuffixes = [
        'Config\.php',
        'DependencyProvider\.php',
    ];

    /**
     * @var bool
     */
    protected $isPublicApi;

    /**
     * @var bool
     */
    protected $isConfiguration;

    /**
     * @var bool
     */
    protected $isFactory;

    /**
     * @var bool
     */
    protected $isInterface;

    /**
     * @var string
     */
    protected $namespace;

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
     * @var string
     */
    protected $className;

    /**
     * @var \ReflectionClass
     */
    protected $reflectionClass;

    /**
     * @var bool
     */
    protected $isParentCore;

    /**
     * @var string
     */
    protected $parentNamespace;

    /**
     * @var string
     */
    protected $parentOrganisation;

    /**
     * @var string
     */
    protected $parentModuleName;

    /**
     * @var string
     */
    protected $parentClassName;

    /**
     * @var string
     */
    protected $parentPackageName;

    /**
     * @var array|null
     */
    protected $usedCorePackageNamesBuffer = null;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @throws \ReflectionException
     *
     * @return void
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
        if ($this->usedCorePackageNamesBuffer !== null) {
            return $this->usedCorePackageNamesBuffer;
        }

        $pattern = sprintf('#\nuse +(%s)\\\\\\w+\\\\(\\w+)\\\\#', implode('|', $this->config->getCoreNamespaces()));
        preg_match_all($pattern, $this->fileContent, $match);

        $this->usedCorePackageNamesBuffer = [];
        foreach ($match[1] as $key => $organisation) {
            $moduleName = $match[2][$key];

            $this->usedCorePackageNamesBuffer[] = SprykerReflectionHelper::namespaceToPackageName($organisation, $moduleName);
        }

        $this->usedCorePackageNamesBuffer = array_unique($this->usedCorePackageNamesBuffer);

        return $this->usedCorePackageNamesBuffer;
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
