<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Version;

class ExpectedVersionBuilder implements ExpectedVersionBuilderInterface
{
    /**
     * @param string $currentVersion
     *
     * @return string
     */
    public function buildExpectedVersion(string $currentVersion): string
    {
        $expectedVersions = [];
        $currentVersions = explode('|', $currentVersion);
        foreach ($currentVersions as $currentVersion) {
            $currentVersion = trim($currentVersion);

            if ($currentVersion[0] === '~') {
                $expectedVersions[] = $currentVersion;

                continue;
            }

            if ($currentVersion[0] === '^' && $currentVersion[1] !== '0') {
                $currentVersion[0] = '~';
                $expectedVersions[] = $currentVersion;

                continue;
            }

            if ($currentVersion[0] === '^' && $currentVersion[1] === '0') {
                $expectedVersions[] = $currentVersion;

                continue;
            }

            $expectedVersions[] = '~' . $currentVersion;
        }

        return implode(' | ', $expectedVersions);
    }
}
