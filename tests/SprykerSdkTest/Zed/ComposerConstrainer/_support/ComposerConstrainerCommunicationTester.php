<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeBridge;
use SprykerSdk\Zed\ModuleFinder\Business\ModuleFinderFacade;

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
class ComposerConstrainerCommunicationTester extends Actor
{
    use _generated\ComposerConstrainerCommunicationTesterActions;

    /**
     * @return void
     */
    public function mockModuleFinder(): void
    {
        $moduleFinderFacadeMock = Stub::make(ModuleFinderFacade::class, [
            'getModules' => function () {
                return $this->getModuleCollection();
            },
        ]);

        $composerConstrainerToModuleFinderFacadeBridge = new ComposerConstrainerToModuleFinderFacadeBridge($moduleFinderFacadeMock);

        $this->mockFactoryMethod('getModuleFinderFacade', $composerConstrainerToModuleFinderFacadeBridge);
    }

    /**
     * @return array
     */
    protected function getModuleCollection(): array
    {
        $sprykerOrganizationTransfer = new OrganizationTransfer();
        $sprykerOrganizationTransfer->setName('Spryker');

        $sprykerShopOrganizationTransfer = new OrganizationTransfer();
        $sprykerShopOrganizationTransfer->setName('SprykerShop');

        $moduleTransferSprykerModuleA = new ModuleTransfer();
        $moduleTransferSprykerModuleA
            ->setName('ModuleA')
            ->setOrganization($sprykerOrganizationTransfer);

        $moduleTransferSprykerModuleB = new ModuleTransfer();
        $moduleTransferSprykerModuleB
            ->setName('ModuleB')
            ->setOrganization($sprykerOrganizationTransfer);

        $moduleTransferSprykerModuleC = new ModuleTransfer();
        $moduleTransferSprykerModuleC
            ->setName('ModuleC')
            ->setOrganization($sprykerOrganizationTransfer);

        $moduleTransferSprykerShopModuleC = new ModuleTransfer();
        $moduleTransferSprykerShopModuleC
            ->setName('ModuleC')
            ->setOrganization($sprykerShopOrganizationTransfer);

        $moduleCollection = [
            $moduleTransferSprykerModuleA,
            $moduleTransferSprykerModuleB,
            $moduleTransferSprykerModuleC,
            $moduleTransferSprykerShopModuleC,
        ];
        return $moduleCollection;
    }
}
