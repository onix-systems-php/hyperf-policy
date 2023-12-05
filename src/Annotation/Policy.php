<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfPolicy\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target ({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Policy extends AbstractAnnotation
{
    public function __construct(public int $priority) {}
}
