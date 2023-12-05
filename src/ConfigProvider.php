<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfPolicy;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                \OnixSystemsPHP\HyperfCore\Contract\CorePolicyGuard::class => \OnixSystemsPHP\HyperfPolicy\Service\PolicyGuardService::class,
                \OnixSystemsPHP\HyperfCore\Contract\CoreDataGuard::class => \OnixSystemsPHP\HyperfPolicy\Service\DataGuardService::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
