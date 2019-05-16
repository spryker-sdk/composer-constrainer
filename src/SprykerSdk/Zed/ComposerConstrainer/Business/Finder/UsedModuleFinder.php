<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Finder;

use Generated\Shared\Transfer\UsedModuleCollectionTransfer;

class UsedModuleFinder implements UsedModuleFinderInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface[]
     */
    protected $finders;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface[] $finders
     */
    public function __construct(array $finders)
    {
        $this->finders = $finders;
    }

    /**
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    public function find(): UsedModuleCollectionTransfer
    {
        $usedModuleCollectionTransfer = new UsedModuleCollectionTransfer();

        foreach ($this->finders as $finder) {
            $usedModuleCollectionTransfer = $this->addToCollection($usedModuleCollectionTransfer, $finder);
        }

        return $usedModuleCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UsedModuleCollectionTransfer $usedModuleCollectionTransfer
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface $usedModuleFinder
     *
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    protected function addToCollection(UsedModuleCollectionTransfer $usedModuleCollectionTransfer, UsedModuleFinderInterface $usedModuleFinder): UsedModuleCollectionTransfer
    {
        foreach ($usedModuleFinder->find()->getUsedModules() as $usedModule) {
            $usedModuleCollectionTransfer->addUsedModule($usedModule);
        }

        return $usedModuleCollectionTransfer;
    }
}
