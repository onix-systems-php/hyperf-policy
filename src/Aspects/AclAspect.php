<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfPolicy\Aspects;

use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Exception\AnnotationException;
use OnixSystemsPHP\HyperfCore\Constants\ErrorCode;
use OnixSystemsPHP\HyperfCore\Contract\CoreAuthenticatableProvider;
use OnixSystemsPHP\HyperfCore\Exception\BusinessException;
use OnixSystemsPHP\HyperfPolicy\Annotation\Acl;

use function Hyperf\Translation\__;

#[Aspect]
final class AclAspect extends AbstractAspect
{
    public array $annotations = [
        Acl::class,
    ];

    public function __construct(private CoreAuthenticatableProvider $authenticatableProvider) {}

    public function process(ProceedingJoinPoint $proceedingJoinPoint): mixed
    {
        $user = $this->authenticatableProvider->user();
        $role = $user?->getRole();
        $aclAnnotation = $this->getAclAnnotation(
            $proceedingJoinPoint->className,
            $proceedingJoinPoint->methodName
        );
        $roleList = $this->arrayRolesParse($aclAnnotation->roles);
        if (! in_array($role, $roleList)) {
            throw new BusinessException(ErrorCode::UNAUTHORIZED_ERROR, __('exceptions.http.401'));
        }
        return $proceedingJoinPoint->process();
    }

    protected function getAclAnnotation(string $className, string $method): Acl
    {
        $annotation = AnnotationCollector::getClassMethodAnnotation($className, $method)[Acl::class] ?? null;
        if (! $annotation instanceof Acl) {
            throw new AnnotationException(__('exceptions.auth.acl_annotation_issue'));
        }

        return $annotation;
    }

    private function arrayRolesParse($roleArray): array
    {
        $roleList = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($roleArray));
        foreach ($iterator as $value) {
            $roleList[] = $value;
        }
        return $roleList;
    }
}
