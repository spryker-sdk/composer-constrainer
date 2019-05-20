<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeBridge;

/**
 * @method \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig getConfig()
 */
class ComposerConstrainerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MODULE_FINDER = 'module finder facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addModuleFinderFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addModuleFinderFacade(Container $container): Container
    {
        $container[static::FACADE_MODULE_FINDER] = function (Container $container) {
            $composerConstrainerToModuleFinderFacadeBridge = new ComposerConstrainerToModuleFinderFacadeBridge(
                $container->getLocator()->moduleFinder()->facade()
            );

            return $composerConstrainerToModuleFinderFacadeBridge;
        };

        return $container;
    }
}
