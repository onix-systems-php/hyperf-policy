<?php

declare(strict_types=1);
namespace OnixSystemsPHP\HyperfPolicy\Service;

use Hyperf\Database\Model\Builder;
use OnixSystemsPHP\HyperfCore\Contract\CoreDataGuard;
use OnixSystemsPHP\HyperfCore\Repository\AbstractRepository;
use OnixSystemsPHP\HyperfCore\Service\Service;
use OnixSystemsPHP\HyperfPolicy\Annotation\DataSpecifier;
use OnixSystemsPHP\HyperfPolicy\Policy\AbstractDataSpecifier;
use Hyperf\Di\Annotation\AnnotationCollector;
use Psr\Container\ContainerInterface;

#[Service]
class DataGuardService implements CoreDataGuard
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function specify(AbstractRepository $repository, Builder $query, string $action = 'list'): Builder
    {
        $specifiers = AnnotationCollector::getClassesByAnnotation(DataSpecifier::class);

        $cmp = function (DataSpecifier $a, DataSpecifier $b) {
            if ($a->priority == $b->priority) {
                return 0;
            }
            return $a->priority > $b->priority ? -1 : 1;
        };
        uasort($specifiers, $cmp);

        /**
         * @var string $specifierClass
         * @var DataSpecifier $annotation
         */
        foreach ($specifiers as $specifierClass => $annotation) {
            /** @var AbstractDataSpecifier $specifier */
            $specifier = $this->container->get($specifierClass);
            if ($specifier->supports($action, $repository)) {
                $query = $specifier->specify($repository, $query, $action);
                if ($annotation->isFinal) {
                    break;
                }
            }
        }

        return $query;
    }
}
