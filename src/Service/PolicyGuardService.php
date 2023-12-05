<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfPolicy\Service;

use Hyperf\Di\Annotation\AnnotationCollector;
use OnixSystemsPHP\HyperfCore\Contract\CorePolicyGuard;
use OnixSystemsPHP\HyperfCore\Service\Service;
use OnixSystemsPHP\HyperfPolicy\Annotation\Policy;
use OnixSystemsPHP\HyperfPolicy\Constants\PolicyVote;
use OnixSystemsPHP\HyperfPolicy\Policy\AbstractPolicy;
use Psr\Container\ContainerInterface;

#[Service]
class PolicyGuardService implements CorePolicyGuard
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function check(string $attribute, mixed $subject, array $options = []): void
    {
        $voters = AnnotationCollector::getClassesByAnnotation(Policy::class);

        $cmp = function (Policy $a, Policy $b) {
            if ($a->priority == $b->priority) {
                return 0;
            }
            return $a->priority > $b->priority ? -1 : 1;
        };
        uasort($voters, $cmp);

        /**
         * @var string $voterClass
         * @var Policy $annotation
         */
        foreach ($voters as $voterClass => $annotation) {
            /** @var AbstractPolicy $voter */
            $voter = $this->container->get($voterClass);
            if ($voter->supports($attribute, $subject)) {
                $result = $voter->vote($attribute, $subject, $options);
                if ($result == PolicyVote::ACCESS_DENIED) {
                    throw $voter->getException($attribute, $subject, $options);
                }
                if ($result == PolicyVote::ACCESS_GRANTED) {
                    break;
                }
            }
        }
    }

    public function justCheck(string $attribute, mixed $subject, array $options = []): bool
    {
        try {
            $this->check($attribute, $subject, $options);
        } catch (\Throwable) {
            return false;
        }
        return true;
    }
}
