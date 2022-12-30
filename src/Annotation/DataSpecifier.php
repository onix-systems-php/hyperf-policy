<?php

declare(strict_types=1);
namespace OnixSystemsPHP\HyperfPolicy\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target ({"CLASS"})
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DataSpecifier extends AbstractAnnotation
{
    public int $priority;
    public bool $isFinal = false;
}
