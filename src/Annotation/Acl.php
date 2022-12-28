<?php

declare(strict_types=1);
namespace OnixSystemsPHP\HyperfPolicy\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target ({"METHOD"})
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Acl extends AbstractAnnotation
{
    public array $roles;
}
