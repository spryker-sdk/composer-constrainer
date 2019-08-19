<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer;

use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ComposerConstrainerBusinessTester extends Actor
{
    use _generated\ComposerConstrainerBusinessTesterActions;

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereModuleClassIsOverridden(): string
    {
        return $this->getVirtualDirectory([
            'src' => [
                'Pyz' => [
                    'Zed' => [
                        'Module' => [
                            'Business' => [
                                'SubDirectory' => [
                                    'FooClass.php' => 'use Spryker\Zed\Module\Business\SubDirectory\FooClass;',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
    /**
     * @return string
     */
    public function getVirtualDirectoryWithOrmAndGeneratedDependencies(): string
    {
        return $this->getVirtualDirectory([
            'src' => [
                'Pyz' => [
                    'Zed' => [
                        'Module' => [
                            'Business' => [
                                'SubDirectory' => [
                                    'FooClass.php' => 'use Orm\Zed\Module\Business\SubDirectory\FooClass;',
                                    'BarClass.php' => 'use Generated\Shared\Transfer\FooBarTransfer;',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereModuleConfigIsOverridden(): string
    {
        return $this->getVirtualDirectory([
            'src' => [
                'Pyz' => [
                    'Zed' => [
                        'Module' => [
                            'ModuleConfig.php' => 'use Spryker\Zed\Module\ModuleConfig;',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getVirtualDirectoryWhereModuleDependencyProviderIsOverridden(): string
    {
        return $this->getVirtualDirectory([
            'src' => [
                'Pyz' => [
                    'Zed' => [
                        'Module' => [
                            'Business' => [
                                'ModuleDependencyProvider.php' => 'use Spryker\Zed\Module\Business\ModuleDependencyProvider;',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
