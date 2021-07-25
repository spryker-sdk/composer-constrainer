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

    protected $configurationSuffixes = [
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
            ->name([ '*.php', '*transfer.xml', '*schema.xml', '*.twig', '*navigation.xml']);

        return $finder;
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
        $packageName = $this->filePathToPackageName('Spryker', $splFileInfo->getRelativePathname());
        if (!isset($usedModules[$packageName])) {
            [$organisation, $module] = $this->packageNameToNamespace($packageName);
            $usedModules[$packageName] = $this->initUsedModuleTransfer($packageName, $organisation, $module);
        }

        if (preg_match('/navigation\.xml/', $splFileInfo->getFilename())) {
            $usedModules[$packageName]->setIsCustomised(true);
        }

        // no changes on UsedModuleTransfer by schema files since they are simple major dependency toward the module

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
     * - Current module, external API class extension: overriding public entity is depenency toward the module's major version
     * - Current module, external API class extension: entity addition or call to a protected/private entity is customisation
     * - Current module, class extension: class extension is customisation
     * - For trail version all customisation lines are added to line count
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

        $content = $splFileInfo->getContents();
        preg_match_all('#\nnamespace +(\\w+\\\\\\w+\\\\(\\w+)[^;]*);#', $content, $match);

        $currentModule = $match[2][0];
        $currentNamespace = $match[1][0];

        preg_match_all('#\n(class|abstract class|interface) +(\\w+)#', $content, $match);
        $currentClassName = $match[2][0];

        $reflection = new \ReflectionClass($currentNamespace . '\\' . $currentClassName);

        $isCurrentExternalApi = preg_match('#(' .implode('|', array_merge($this->publicApiClassSuffixes, $this->publicApiInterfaceSuffixes)). ')#', $reflection->getFileName());
        $isCurrentConfiguration = preg_match('#(' .implode('|', $this->configurationSuffixes). ')#', $reflection->getFileName());

        $parentNamespace = $reflection->getParentClass() ? $reflection->getParentClass()->getNamespaceName() : "";
        $parentClassName = $reflection->getParentClass() ? $reflection->getParentClass()->getShortName() : "";
        preg_match_all('#(\\w+)\\\\\\w+\\\\(\\w+)#', $parentNamespace, $match);
        $parentOrganisation = count($match[1]) > 0 ? $match[1][0] : "";
        $parentModule = count($match[2]) > 0 ? $match[2][0] : "";

        if (!($parentModule && in_array($parentOrganisation, $this->config->getCoreNamespaces(), true))) { // if class is NOT extended from core, it's irrelevant for investigation
            return $usedModules;
        }

        $parentPackageName = $this->namespaceToPackageName($parentOrganisation, $parentModule);

        if (!$isCurrentExternalApi) {
            if (!isset($usedModules[$parentPackageName])) {
                $usedModules[$parentPackageName] = $this->initUsedModuleTransfer($parentPackageName, $parentOrganisation, $parentModule);
            }
            $usedModules[$parentPackageName]->setIsCustomised(true);
            foreach ($reflection->getMethods() as $method) {
                $usedModules[$parentPackageName]->setCustomisedLineCount(
                    $usedModules[$parentPackageName]->getCustomisedLineCount() - $reflection->getStartLine() + ($reflection->getEndLine() ?: $reflection->getStartLine())
                );
            }

            return $usedModules;
        }

        $parentReflection = new \ReflectionClass($parentNamespace . '\\' . $parentClassName);
        $parentMethods = [];
        foreach($parentReflection->getMethods() as $method) {
            $parentMethods[$method->getShortName()] = $method;
        }

        $isPublicMethodOverriden = false;
        $isProtectedMethodDefined = false;
        foreach($reflection->getMethods() as $method) {
            $isPublic = ($method->getModifiers() & \ReflectionMethod::IS_PUBLIC) > 0;
            $isProtected = ($method->getModifiers() & (\ReflectionMethod::IS_PROTECTED + \ReflectionMethod::IS_PRIVATE)) > 0;
            $isPublicMethodOverriden = $isPublicMethodOverriden || $isPublic && array_key_exists($method->getShortName(), $parentMethods);
            $isProtectedMethodDefined = $isProtectedMethodDefined || $isProtected;
        }

        if (!($isPublicMethodOverriden || $isProtectedMethodDefined)) {
            return $usedModules;
        }

        if (!isset($usedModules[$parentPackageName])) {
            $usedModules[$parentPackageName] = $this->initUsedModuleTransfer($parentPackageName, $parentOrganisation, $parentModule);
        }
        if ($isPublicMethodOverriden && $isCurrentConfiguration) {
            $usedModules[$parentPackageName]->setIsConfigured(true);

            return $usedModules;
        }

        $usedModules[$parentPackageName]->setIsCustomised(true);

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
            if (isset($usedModules[$packageName])) {
                continue;
            }

            $usedModules[$packageName] = $this->initUsedModuleTransfer($packageName, $organisation, $module);
        }

        return $usedModules;
    }

    /**
     * @example (SprykerEco, Zed/ExampleModuleName/anyfile.xml) => spryker-eco/example-module-name
     *
     * @return string
     */
    protected function filePathToPackageName(string $organisation, string $filepath): string
    {
        $transformer = function(string $camelCase):string {
            return strtolower(preg_replace('%([A-Z])([a-z])%', '-\1\2', lcfirst($camelCase)));
        };

        preg_match_all('#^[^/]*/(?<module>[^/]*)/#', $filepath, $match);

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
            ->setPackageName($packageName);
    }
}
