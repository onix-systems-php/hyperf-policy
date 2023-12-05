<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfPolicy\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class PolicyVote extends AbstractConstants
{
    public const ACCESS_GRANTED = 1;

    public const ACCESS_ABSTAIN = 0;

    public const ACCESS_DENIED = -1;
}
