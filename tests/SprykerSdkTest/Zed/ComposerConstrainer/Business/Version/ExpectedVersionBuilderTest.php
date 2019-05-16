<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Business\Version;

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

use Codeception\Test\Unit;
use SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilder;

class ExpectedVersionBuilderTest extends Unit
{
    /**
     * @dataProvider getVersionMap
     *
     * @param string $givenVersion
     * @param string $expectedVersion
     *
     * @return void
     */
    public function testBuildExpectedVersionBuildsExpectedVersion(string $givenVersion, string $expectedVersion): void
    {
        $expectedVersionBuilder = new ExpectedVersionBuilder();

        $this->assertSame($expectedVersion, $expectedVersionBuilder->buildExpectedVersion($givenVersion));
    }

    /**
     * @return string[][]
     */
    public function getVersionMap(): array
    {
        return [
            ['^1.0.0', '~1.0.0'],
            ['~1.0.0', '~1.0.0'],
            ['^0.1.0', '^0.1.0'],
        ];
    }
}
