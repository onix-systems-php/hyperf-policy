<?php

declare(strict_types=1);
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
