<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade;

class ComposerConstrainerToModuleFinderFacadeBridge implements ComposerConstrainerToModuleFinderFacadeInterface
{
    /**
     * @var \SprykerSdk\Zed\ModuleFinder\Business\ModuleFinderFacadeInterface
     */
    protected $moduleFinderFacade;

    /**
     * @param \SprykerSdk\Zed\ModuleFinder\Business\ModuleFinderFacadeInterface $moduleFinderFacade
     */
    public function __construct($moduleFinderFacade)
    {
        $this->moduleFinderFacade = $moduleFinderFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(): array
    {
        return $this->moduleFinderFacade->getModules();
    }
}
