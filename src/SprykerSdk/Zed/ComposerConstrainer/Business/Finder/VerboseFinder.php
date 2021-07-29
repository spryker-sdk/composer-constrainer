<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Finder;

use Generated\Shared\Transfer\UsedModuleCollectionTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\StringSourceLocator;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class VerboseFinder implements FinderInterface
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

        $usedModules =[];
        foreach ($this->createFinder() as $splFileInfo) {
            switch($splFileInfo->getExtension()) {
                case "php":
                    $usedModules = $this->checkPhpCustomisation($usedModules, $splFileInfo);
                    $usedModules = $this->checkPhpDependencies($usedModules, $splFileInfo);
                    break;
                case "xml":
                    $usedModules = $this->addXmlUsedModules($usedModules, $splFileInfo);
                    break;
                case "yaml":
                    $usedModules = $this->addYamlUsedModules($usedModules, $splFileInfo);
                    break;
                case "twig":
                    $usedModules = $this->addTwigUsedModules($usedModules, $splFileInfo);
                    break;
            }
        }

        return $usedModuleCollectionTransfer->setUsedModules(new \ArrayObject($usedModules));
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function createFinder(): Finder
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in( 'src/Pyz/')
            ->exclude(['Generated', 'Orm'])
            ->name([ '*.php', '*transfer.xml', '*schema.xml', '*.twig', '*navigation.xml', '*validation.yaml']);

        return $finder;
    }

    /**
     * Specification:
     * - Validation changes are customisiation
     * - Validation files are located in Spryker and SprykerEco namespace
     * - Validation changes CAN NOT be transformed to pluggable so line count is irrelevant
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function addYamlUsedModules(array $usedModules, SplFileInfo $splFileInfo): array
    {
        // TODO Selector between Spryker and SprykerEco
        $packageName = $this->relativeFilePathToPackageName('Spryker', $splFileInfo->getRelativePathname());
        if (!isset($usedModules[$packageName])) {
            [$organisation, $module] = $this->packageNameToNamespace($packageName);
            $usedModules[$packageName] = $this->initUsedModuleTransfer($packageName, $organisation, $module);
        }

        $usedModules[$packageName]->setIsCustomised(true);
        $usedModules[$packageName]->addConstraintReason('Customised: validation.yaml defined');

        return $usedModules;
    }

    /**
     * Specification:
     * - Transfer definitions are NOT considered customisation or configuration or dependency
     * - Navigation changes are customisiation
     * - Navigation files are located in Spryker and SprykerEco namespace
     * - Navigation changes CAN NOT be transformed to pluggable so line count is irrelevant
     * - Schema changes are dependency toward the module's major version
     * - Schema files are located in Spryker and SprykerEco namespace
     * - Schema changes CAN NOT be transformed to pluggable so line count is irrelevant
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function addXmlUsedModules(array $usedModules, SplFileInfo $splFileInfo): array
    {
        if (preg_match('/transfer\.xml/', $splFileInfo->getFilename())) {
            return $usedModules;
        }

        // TODO Selector between Spryker and SprykerEco
        $packageName = $this->relativeFilePathToPackageName('Spryker', $splFileInfo->getRelativePathname());
        if (!isset($usedModules[$packageName])) {
            [$organisation, $module] = $this->packageNameToNamespace($packageName);
            $usedModules[$packageName] = $this->initUsedModuleTransfer($packageName, $organisation, $module);
        }

        if (preg_match('/navigation\.xml/', $splFileInfo->getFilename())) {
            $usedModules[$packageName]->setIsCustomised(true);
            $usedModules[$packageName]->addConstraintReason('Customised: navigation.xml defined');

            return $usedModules;
        }

        $usedModules[$packageName]->addConstraintReason('Dependency: schema.xml');

        return $usedModules;
    }

    /**
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param SplFileInfo $splFileInfo
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
     * - Config and dependency provider class extension: entity addition or call to a protected/private entity is customisation
     * - External API class extension: public entity overriding is depenency toward the module's major version
     * - External API class extension: entity addition or call to a protected/private entity is customisation
     * - Class extension: class extension is customisation
     * - For trail version all customisation lines are added to line count except Factory changes and adding public API public entity
     *
     * @param \Generated\Shared\Transfer\UsedModuleTransfer[] $usedModules
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer[]
     */
    protected function checkPhpCustomisation(array $usedModules, SplFileInfo $splFileInfo): array
    {
        // TODO creating new class that is not extended from core is also customisation if there is such core module
        // TODO calling protected/private entity in an extended external API class is customisation
        // TODO adding constants & properties on top of method checks

        $content = $splFileInfo->getContents();
        preg_match_all('#\nnamespace +(\\w+\\\\\\w+\\\\(\\w+)[^;]*);#', $content, $match);

        $currentModule = $match[2][0];
        $currentNamespace = $match[1][0];

        preg_match_all('#\n(class|abstract class|interface) +(\\w+)#', $content, $match);
        $currentClassName = $match[2][0];

        $currentClassReflection = new \ReflectionClass($currentNamespace . '\\' . $currentClassName);

        $isCurrentExternalApiClass = preg_match('#(' .implode('|', array_merge($this->publicApiClassSuffixes, $this->publicApiInterfaceSuffixes)). ')#', $currentClassReflection->getFileName());
        $isCurrentConfigurationClass = preg_match('#(' .implode('|', $this->configurationClassSuffixes). ')#', $currentClassReflection->getFileName());
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

        $parentPackageName = $this->namespaceToPackageName($parentOrganisation, $parentModule);

        if (!$isCurrentExternalApiClass) {
            if (!isset($usedModules[$parentPackageName])) {
                $usedModules[$parentPackageName] = $this->initUsedModuleTransfer($parentPackageName, $parentOrganisation, $parentModule);
            }
            $usedModules[$parentPackageName]->setIsCustomised(true);
            foreach ($currentClassReflection->getMethods() as $method) {
                if ($method->getDeclaringClass()->getNamespaceName() !== $currentClassReflection->getNamespaceName()) { // only interested in project methods
                    continue;
                }

                if ($isFactory) {
                    $usedModules[$parentPackageName]->addConstraintReason('Customised: ' . $currentClassName . '::' . $method->getShortName() . '()');
                } else {
                    $customisedLineCount = ($method->getEndLine() ?: $method->getStartLine()) - $method->getStartLine();
                    $customisedLineCount -= ($method->isAbstract() || $currentClassReflection->isInterface()) ? 0 : 2;
                    $usedModules[$parentPackageName]->setCustomisedLineCount($usedModules[$parentPackageName]->getCustomisedLineCount() + $customisedLineCount);
                    $usedModules[$parentPackageName]->addConstraintReason('Customised: ' . $currentClassName . '::' . $method->getShortName() . '()' .  ' - '  . $customisedLineCount);
                }
            }

            return $usedModules;
        }

        $parentClassReflection = new \ReflectionClass($parentNamespace . '\\' . $parentClassName);
        $parentMethods = [];
        foreach($parentClassReflection->getMethods() as $method) {
            $parentMethods[$method->getShortName()] = $method->getShortName();
        }

        foreach($currentClassReflection->getMethods() as $method) {
            if ($method->getDeclaringClass()->getNamespaceName() !== $currentClassReflection->getNamespaceName()) { // only interested in project methods
                continue;
            }

            $isPublic = ($method->getModifiers() & \ReflectionMethod::IS_PUBLIC) > 0;
            $isProtected = ($method->getModifiers() & (\ReflectionMethod::IS_PROTECTED + \ReflectionMethod::IS_PRIVATE)) > 0;
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

            $usedModules[$parentPackageName]->setIsCustomised(true);

            if ($isPublic) {
                $usedModules[$parentPackageName]->addConstraintReason('Customised: ' . $currentClassName . '::' . $method->getShortName() . '()');
            } else {
                $customisedLineCount = ($method->getEndLine() ?: $method->getStartLine()) - $method->getStartLine();
                $customisedLineCount -= ($method->isAbstract() || $currentClassReflection->isInterface()) ? 0 : 2;
                $usedModules[$parentPackageName]->setCustomisedLineCount($usedModules[$parentPackageName]->getCustomisedLineCount() + $customisedLineCount);
                $usedModules[$parentPackageName]->addConstraintReason('Customised: ' . $currentClassName . '::' . $method->getShortName() . '()' .  ' - '  . $customisedLineCount);
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
    protected function checkPhpDependencies(array $usedModules, SplFileInfo $splFileInfo): array
    {
        // TODO Another core module: forcing module dependency with "@module" is dependency toward that module's major version

        $content = $splFileInfo->getContents();
        $pattern = sprintf('#\nuse +(%s)\\\\\\w+\\\\(\\w+)\\\\#',
            implode('|', $this->config->getCoreNamespaces())
        );
        preg_match_all($pattern, $content, $match);

        foreach($match[1] as $key => $organisation) {
            $module = $match[2][$key];

            $packageName = $this->namespaceToPackageName($organisation, $module);
            if (!isset($usedModules[$packageName])) {
                $usedModules[$packageName] = $this->initUsedModuleTransfer($packageName, $organisation, $module);
            }

            $usedModules[$packageName]->addConstraintReason('Dependency: incoming from ' . $splFileInfo->getFilename());
        }

        return $usedModules;
    }

    /**
     * @example (SprykerEco, Zed/ExampleModuleName/anyfile.xml) => spryker-eco/example-module-name
     *
     * @return string
     */
    protected function relativeFilePathToPackageName(string $organisation, string $relativeFilepath): string
    {
        $transformer = function(string $camelCase):string {
            return strtolower(preg_replace('%([A-Z])([a-z])%', '-\1\2', lcfirst($camelCase)));
        };

        preg_match_all('#^[^/]*/(?<module>[^/]*)/#', $relativeFilepath, $match);

        return $transformer($organisation) . '/' . $transformer($match['module'][0]);
    }

    /**
     * @example (SprykerEco, ExampleModuleName) => spryker-eco/example-module-name
     *
     * @param string $organisation
     * @param string $moduleName
     *
     * @return string
     */
    protected function namespaceToPackageName(string $organisation, string $moduleName): string
    {
        $transformer = function(string $camelCase):string {
            return strtolower(preg_replace('%([A-Z])([a-z])%', '-\1\2', lcfirst($camelCase)));
        };

        return $transformer($organisation) . '/' . $transformer($moduleName);
    }

    /**
     * @example spryker-eco/example-module-name => [SprykerEco, ExampleModuleName]
     *
     * @param string $packageName
     *
     * @return string[]
     */
    protected function packageNameToNamespace(string $packageName): array
    {
        $transformer = function(string $dashed):string {
            return str_replace(' ', '', ucfirst(str_replace('-', ' ', $dashed)));
        };

        return array_map($transformer, explode('/', $packageName));
    }

    protected function initUsedModuleTransfer(string $packageName, string $organisation, string $module): UsedModuleTransfer
    {
        return (new UsedModuleTransfer())
            ->setIsConfigured(false)
            ->setIsCustomised(false)
            ->setCustomisedLineCount(0)
            ->setModule($module)
            ->setOrganization($organisation)
            ->setPackageName($packageName)
            ->setConstraintReasons([]);
    }
}
