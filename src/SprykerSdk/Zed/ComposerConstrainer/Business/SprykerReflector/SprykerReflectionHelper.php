<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\SprykerReflector;

class SprykerReflectionHelper
{
    /**
     * @example spryker-eco/example-module-name => [SprykerEco, ExampleModuleName]
     *
     * @param string $packageName
     *
     * @return array<string>
     */
    public static function packageNameToNamespace(string $packageName): array
    {
        $transformer = function (string $dashed): string {
            return str_replace(' ', '', ucfirst(str_replace('-', ' ', $dashed)));
        };

        return array_map($transformer, explode('/', $packageName));
    }

    /**
     * @example (SprykerEco, ExampleModuleName) => spryker-eco/example-module-name
     *
     * @param string $organisation
     * @param string $moduleName
     *
     * @return string
     */
    public static function namespaceToPackageName(string $organisation, string $moduleName): string
    {
        $transformer = function (string $camelCase): string {
            return strtolower((string)preg_replace('%([A-Z])([a-z])%', '-\1\2', lcfirst($camelCase)));
        };

        return $transformer($organisation) . '/' . $transformer($moduleName);
    }

    /**
     * @example (SprykerEco, Pyz/Zed/ExampleModuleName/anyfile.xml) => spryker-eco/example-module-name
     *
     * @param string $organisation
     * @param string $relativeFilepath
     *
     * @return string
     */
    public static function relativeFilePathToPackageName(string $organisation, string $relativeFilepath): string
    {
        $transformer = function (string $camelCase): string {
            return strtolower((string)preg_replace('%([A-Z])([a-z])%', '-\1\2', lcfirst($camelCase)));
        };

        preg_match_all('#^[^/]*/[^/]*/(?<module>[^/]*)/#', $relativeFilepath, $match);

        return $transformer($organisation) . '/' . $transformer($match['module'][0]);
    }
}
