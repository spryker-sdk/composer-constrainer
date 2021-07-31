<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Finder;

use ArrayObject;
use Generated\Shared\Transfer\UsedModuleCollectionTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use ReflectionClass;
use ReflectionMethod;
use SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector\SprykerClassReflector;
use SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector\SprykerReflectionHelper;
use SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector\SprykerXmlReflector;
use SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector\SprykerYamlReflector;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class StrictFinder implements FinderInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    protected $publicApiClassSuffixes = [
        'Service.php',
        'Client.php',
        'Facade.php',
        'QueryContainer.php',
        'Controller.php',
        'Config.php',
        'DependencyProvider.php',
    ];

    protected $publicApiInterfaceSuffixes = [
        'ServiceInterface.php',
        'ClientInterface.php',
        'FacadeInterface.php',
        'QueryContainerInterface.php',
        'PluginInterface.php',
    ];

    protected $configurationClassSuffixes = [
        'Config.php',
        'DependencyProvider.php',
    ];

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     */
    public function __construct(ComposerConstrainerConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    public function find(): UsedModuleCollectionTransfer
    {
        $usedModuleCollectionTransfer = new UsedModuleCollectionTransfer();

        if (!is_dir($this->config->getSourceDirectory())) {
            return $usedModuleCollectionTransfer;
        }

        $usedModules = [];
        foreach ($this->createFinder() as $splFileInfo) {
            switch ($splFileInfo->getExtension()) {
                case "php":
                    $sprykerClassReflector = new SprykerClassReflector($this->config, $splFileInfo);
                    $usedModules = $this->checkPhpCustomization($usedModules, $splFileInfo);
                    $usedModules = $this->checkPhpDependencies($usedModules, $sprykerClassReflector);
                    break;
                case "xml":
                    $sprykerXmlReflector = new SprykerXmlReflector($this->config, $splFileInfo);
                    $usedModules = $this->addXmlUsedModules($usedModules, $sprykerXmlReflector);
                    break;
                case "yaml":
                    $sprykerYamlReflector = new SprykerYamlReflector($this->config, $splFileInfo);
                    $usedModules = $this->addYamlUsedModules($usedModules, $sprykerYamlReflector);
                    break;
                case "twig":
                    $usedModules = $this->addTwigUsedModules($usedModules, $splFileInfo);
                    break;
            }
        }

        return $usedModuleCollectionTransfer->setUsedModules(new ArrayObject($usedModules));
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function createFinder(): Finder
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in('src/Pyz/') // TODO needs to come from config
            ->exclude(['Generated', 'Orm'])
            ->name([ '*.php', '*transfer.xml', '*schema.xml', '*.twig', '*navigation.xml', '*validation.yaml']);

        return $finder;
    }

    /**
     * Specification:
     * - Validation changes are customiziation
     * - Validation changes CAN NOT be transformed to pluggable so no impact on line count
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function addYamlUsedModules(array $usedModules, SprykerYamlReflector $sprykerYamlReflector): array
    {
        $usedModuleTransfer = $this->setrieveUsedModule(
            $usedModules,
            $sprykerYamlReflector->getPackageName(),
            $sprykerYamlReflector->getOrganisation(),
            $sprykerYamlReflector->getModuleName()
        );

        $usedModuleTransfer
            ->setIsCustomized(true)
            ->addConstraintReason('Customized: validation.yaml defined');

        return $usedModules;
    }

    /**
     * Specification:
     * - Transfer definitions are NOT considered customization or configuration or dependency
     * - Navigation changes are customiziation
     * - Navigation changes CAN NOT be transformed to pluggable so no impact on line count
     * - Schema changes are dependency toward the module's major version
     * - Schema changes CAN NOT be transformed to pluggable so no impact on line count
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function addXmlUsedModules(array $usedModules, SprykerXmlReflector $sprykerXmlReflector): array
    {
        if ($sprykerXmlReflector->getIsTransfer()) {
            return $usedModules;
        }

        $usedModuleTransfer = $this->setrieveUsedModule(
            $usedModules,
            $sprykerXmlReflector->getPackageName(),
            $sprykerXmlReflector->getOrganisation(),
            $sprykerXmlReflector->getModuleName()
        );

        if ($sprykerXmlReflector->getIsNavigation()) {
            $usedModuleTransfer
                ->setIsCustomized(true)
                ->addConstraintReason('Customized: navigation.xml defined');

            return $usedModules;
        }

        $usedModuleTransfer->addConstraintReason('Dependency: schema.xml');

        return $usedModules;
    }

    /**
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function addTwigUsedModules(array $usedModules, SplFileInfo $splFileInfo): array
    {
        // TODO

        return $usedModules;
    }

    /**
     * Specification
     * - Config and dependency provider class extension: public entity overriding is configuration
     * - Config and dependency provider class extension: entity addition or call to a protected/private entity is customization
     * - External API class extension: public entity overriding is depenency toward the module's major version
     * - External API class extension: entity addition or call to a protected/private entity is customization
     * - Class extension: class extension is customization
     * - For trail version all customization lines are added to line count except Factory changes and adding public API public entity
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function checkPhpCustomization(array $usedModules, SplFileInfo $splFileInfo): array
    {
        // TODO creating new class that is not extended from core is also customization if there is such core module
        // TODO calling protected/private entity in an extended external API class is customization
        // TODO adding constants & properties on top of method checks

        $content = $splFileInfo->getContents();
        preg_match_all('#\nnamespace +(\\w+\\\\\\w+\\\\(\\w+)[^;]*);#', $content, $match);

        $currentModule = $match[2][0];
        $currentNamespace = $match[1][0];

        preg_match_all('#\n(class|abstract class|interface) +(\\w+)#', $content, $match);
        $currentClassName = $match[2][0];

        $currentClassReflection = new ReflectionClass($currentNamespace . '\\' . $currentClassName);

        $isCurrentExternalApiClass = preg_match('#(' . implode('|', array_merge($this->publicApiClassSuffixes, $this->publicApiInterfaceSuffixes)) . ')#', $currentClassReflection->getFileName());
        $isCurrentConfigurationClass = preg_match('#(' . implode('|', $this->configurationClassSuffixes) . ')#', $currentClassReflection->getFileName());
        $isFactory = preg_match('#Factory.php#', $currentClassReflection->getFileName());

        $parentNamespace = $currentClassReflection->getParentClass() ? $currentClassReflection->getParentClass()->getNamespaceName() : "";
        $parentClassName = $currentClassReflection->getParentClass() ? $currentClassReflection->getParentClass()->getShortName() : "";
        preg_match_all('#(\\w+)\\\\\\w+\\\\(\\w+)#', $parentNamespace, $match);
        $parentOrganisation = count($match[1]) > 0 ? $match[1][0] : "";
        $parentModule = count($match[2]) > 0 ? $match[2][0] : "";

        $isParentFromCore = $parentModule && in_array($parentOrganisation, $this->config->getCoreNamespaces(), true);
        if (!$isParentFromCore) {
            return $usedModules;
        }

        $parentPackageName = SprykerReflectionHelper::namespaceToPackageName($parentOrganisation, $parentModule);

        if (!$isCurrentExternalApiClass) {
            if (!isset($usedModules[$parentPackageName])) {
                $usedModules[$parentPackageName] = $this->initUsedModuleTransfer($parentPackageName, $parentOrganisation, $parentModule);
            }
            $usedModules[$parentPackageName]->setIsCustomized(true);
            foreach ($currentClassReflection->getMethods() as $method) {
                if ($method->getDeclaringClass()->getNamespaceName() !== $currentClassReflection->getNamespaceName()) { // only interested in project methods
                    continue;
                }

                if ($isFactory) {
                    $usedModules[$parentPackageName]->addConstraintReason('Customized: ' . $currentClassName . '::' . $method->getShortName() . '()');
                } else {
                    $customizedLineCount = ($method->getEndLine() ?: $method->getStartLine()) - $method->getStartLine();
                    $customizedLineCount -= ($method->isAbstract() || $currentClassReflection->isInterface()) ? 0 : 2;
                    $usedModules[$parentPackageName]->setCustomizedLineCount($usedModules[$parentPackageName]->getCustomizedLineCount() + $customizedLineCount);
                    $usedModules[$parentPackageName]->addConstraintReason('Customized: ' . $currentClassName . '::' . $method->getShortName() . '()' . ' - ' . $customizedLineCount);
                }
            }

            return $usedModules;
        }

        $parentClassReflection = new ReflectionClass($parentNamespace . '\\' . $parentClassName);
        $parentMethods = [];
        foreach ($parentClassReflection->getMethods() as $method) {
            $parentMethods[$method->getShortName()] = $method->getShortName();
        }

        foreach ($currentClassReflection->getMethods() as $method) {
            if ($method->getDeclaringClass()->getNamespaceName() !== $currentClassReflection->getNamespaceName()) { // only interested in project methods
                continue;
            }

            $isPublic = ($method->getModifiers() & ReflectionMethod::IS_PUBLIC) > 0;
            $isProtected = ($method->getModifiers() & (ReflectionMethod::IS_PROTECTED + ReflectionMethod::IS_PRIVATE)) > 0;
            $isPublicMethodOverriden = $isPublic && array_key_exists($method->getShortName(), $parentMethods);

            if (!isset($usedModules[$parentPackageName])) {
                $usedModules[$parentPackageName] = $this->initUsedModuleTransfer($parentPackageName, $parentOrganisation, $parentModule);
            }

            if ($isPublicMethodOverriden) {
                if ($isCurrentConfigurationClass) {
                    $usedModules[$parentPackageName]->setIsConfigured(true);
                    $usedModules[$parentPackageName]->addConstraintReason('Configured: public method overriden ' . $currentClassName . '::' . $method->getShortName() . '()');
                } else {
                    $usedModules[$parentPackageName]->setIsConfigured(true);
                    $usedModules[$parentPackageName]->addConstraintReason('Dependency: public API method overriden ' . $currentClassName . '::' . $method->getShortName() . '()');
                }

                continue;
            }

            $usedModules[$parentPackageName]->setIsCustomized(true);

            if ($isPublic) {
                $usedModules[$parentPackageName]->addConstraintReason('Customized: ' . $currentClassName . '::' . $method->getShortName() . '()');
            } else {
                $customizedLineCount = ($method->getEndLine() ?: $method->getStartLine()) - $method->getStartLine();
                $customizedLineCount -= ($method->isAbstract() || $currentClassReflection->isInterface()) ? 0 : 2;
                $usedModules[$parentPackageName]->setCustomizedLineCount($usedModules[$parentPackageName]->getCustomizedLineCount() + $customizedLineCount);
                $usedModules[$parentPackageName]->addConstraintReason('Customized: ' . $currentClassName . '::' . $method->getShortName() . '()' . ' - ' . $customizedLineCount);
            }
        }

        return $usedModules;
    }

    /**
     * Specification
     * - Another core module: using another core module's external API is dependency toward that module's major version
     * - Another core module: using another core module's non-external API is prohibited
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function checkPhpDependencies(array $usedModules, SprykerClassReflector $sprykerClassReflector): array
    {
        // TODO Another core module: forcing module dependency with "@module" is dependency toward that module's major version

        foreach ($sprykerClassReflector->getUsedCorePackageNames() as $usedCorePackageName) {
            [$usedOrganisation, $usedModuleName] = SprykerReflectionHelper::packageNameToNamespace($usedCorePackageName);
            $usedModuleTransfer = $this->setrieveUsedModule(
                $usedModules,
                $usedCorePackageName,
                $usedOrganisation,
                $usedModuleName,
            );

            $usedModuleTransfer->addConstraintReason('Dependency: incoming from ' . $sprykerClassReflector->getFileName());
        }

        return $usedModules;
    }

    /**
     * Specification:
     * - Initiates missing searched element in the provided array by reference.
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] &$usedModules
     * @param string $packageName
     * @param string $organisation
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer
     */
    protected function setrieveUsedModule(array &$usedModules, string $packageName, string $organisation, string $moduleName): UsedModuleTransfer
    {
        if (!isset($usedModules[$packageName])) {
            $usedModules[$packageName] = (new UsedModuleTransfer())
                ->setIsConfigured(false)
                ->setIsCustomized(false)
                ->setCustomizedLineCount(0)
                ->setModule($moduleName)
                ->setOrganization($organisation)
                ->setPackageName($packageName)
                ->setConstraintReasons([]);
        }

        return $usedModules[$packageName];
    }

    /**
     * @param string $packageName
     * @param string $organisation
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer
     */
    protected function initUsedModuleTransfer(string $packageName, string $organisation, string $module): UsedModuleTransfer
    {
        return (new UsedModuleTransfer())
            ->setIsConfigured(false)
            ->setIsCustomized(false)
            ->setCustomizedLineCount(0)
            ->setModule($module)
            ->setOrganization($organisation)
            ->setPackageName($packageName)
            ->setConstraintReasons([]);
    }
}
