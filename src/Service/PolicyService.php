<?php

declare(strict_types=1);
namespace OnixSystemsPHP\HyperfPolicy\Service;

use OnixSystemsPHP\HyperfCore\Service\Service;
use OnixSystemsPHP\HyperfPolicy\Annotation\Policy;
use OnixSystemsPHP\HyperfPolicy\Constants\PolicyVote;
use OnixSystemsPHP\HyperfPolicy\Policy\AbstractPolicy;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Server\Exception\ServerException;
use Psr\Container\ContainerInterface;

#[Service]
class PolicyService
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     * @throws ServerException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function run(string $attribute, mixed $subject): void
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
                $result = $voter->vote($attribute, $subject);
                if ($result == PolicyVote::ACCESS_DENIED) {
                    throw $voter->getException($attribute, $subject);
                }
                if ($result == PolicyVote::ACCESS_GRANTED) {
                    break;
                }
            }
        }
    }
}
