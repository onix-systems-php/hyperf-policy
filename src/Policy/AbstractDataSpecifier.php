<?php

declare(strict_types=1);
namespace OnixSystemsPHP\HyperfPolicy\Policy;

use Hyperf\Database\Model\Builder;
use OnixSystemsPHP\HyperfCore\Contract\CoreAuthenticatableProvider;
use OnixSystemsPHP\HyperfCore\Repository\AbstractRepository;

abstract class AbstractDataSpecifier
{
    public function __construct(
        protected CoreAuthenticatableProvider $authenticatableProvider,
    ) {
    }

    /**
     * Determines if the action and repository are supported by this specifier.
     */
    abstract public function supports(string $action, AbstractRepository $repository): bool;

    // Specify query to fit user's permissions
    abstract public function specify(AbstractRepository $repository, Builder $query, string $action): Builder;
}
