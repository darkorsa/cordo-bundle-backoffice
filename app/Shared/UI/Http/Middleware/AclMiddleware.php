<?php

namespace App\Backoffice\Shared\UI\Http\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Cordo\Core\SharedKernel\Enum\SystemRole;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AclMiddleware implements MiddlewareInterface
{
    private const HTTP_UNAUTHORIZED = 401;

    private $container;

    private $acl;

    private $service;

    private $resource;

    private $privilage;

    public function __construct(ContainerInterface $container, string $resource, ?string $privilage = null)
    {
        $this->container = $container;
        $this->acl = $container->get('acl');
        $this->service = $container->get('backoffice.acl.query.service');
        $this->resource = $resource;
        $this->privilage = $privilage;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $priviledge = $this->privilage ?: strtolower($request->getMethod());

        $role = $this->getSystemRole($request);
        if ($request->getAttribute('user_id')) {
            $this->service->setUserAclPrivileges($role, $request->getAttribute('user_id'), $this->acl);
        }

        if (!$this->acl->isAllowed($role, $this->resource, $priviledge)) {
            return new Response(self::HTTP_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }

    private function getSystemRole(ServerRequestInterface $request): SystemRole
    {
        return $request->getAttribute('user_id')
            ? SystemRole::LOGGED()
            : SystemRole::GUEST();
    }
}
