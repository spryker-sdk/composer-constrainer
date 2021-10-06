<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Shared\ComposerConstrainer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ComposerConstrainerConstants
{
    /**
     * Specification:
     * - Returns a list of configured core namespaces which are used to find used core dependencies.
     *
     * @uses \Spryker\Shared\Kernel\KernelConstants::CORE_NAMESPACES
     *
     * @api
     * @var string
     */
    public const CORE_NAMESPACES = 'CORE_NAMESPACES';

    /**
     * Specification:
     * - Returns a list of configured project namespaces which are used to separate vendor namespaces from projects.
     *
     * @uses \Spryker\Shared\Kernel\KernelConstants::PROJECT_NAMESPACES
     *
     * @api
     * @var string
     */
    public const PROJECT_NAMESPACES = 'PROJECT_NAMESPACES';
}
