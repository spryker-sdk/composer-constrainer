<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade;

interface ComposerConstrainerToModuleFinderFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(): array;
}
