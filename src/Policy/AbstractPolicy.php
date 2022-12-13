<?php
declare(strict_types=1);

namespace OnixSystemsPHP\HyperfPolicy\Policy;

use Hyperf\Server\Exception\ServerException;

abstract class AbstractPolicy
{

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param $subject mixed The subject to secure, e.g. an object the user wants to access or any other PHP type
     */
    abstract public function supports(string $attribute, mixed $subject): bool;

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     * Must return constant from OnixSystemsPHP\HyperfPolicy\Constants\PolicyVote
     */
    abstract public function vote(string $attribute, mixed $subject): int;

    abstract public function getException(string $attribute, mixed $subject): ServerException;
}
