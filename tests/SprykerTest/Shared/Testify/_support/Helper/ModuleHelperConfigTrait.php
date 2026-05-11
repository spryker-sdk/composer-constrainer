<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Lib\ModuleContainer;

// Compatibility shim: spryker/testify <=3.46.x ships this trait inside the package's own tests/
// but the autoload-dev entry was added later. TransferGenerateHelper requires it at class-load time,
// so we provide it here to avoid a fatal error when the installed testify version is too old.
trait ModuleHelperConfigTrait
{
    public function __construct(ModuleContainer $moduleContainer, ?array $config = null)
    {
        $this->setDefaultConfig();

        parent::__construct($moduleContainer, $config);
    }

    abstract protected function setDefaultConfig(): void;
}
