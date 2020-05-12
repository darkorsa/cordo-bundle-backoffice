<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Acl;

use Cordo\Core\SharedKernel\Enum\SystemRole;
use Cordo\Core\Application\Service\Register\AclRegister;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;

class UsersAcl extends AclRegister
{
    public function register(): void
    {
        $resource = 'backoffice\users';

        $this->acl->addResource(new Resource($resource))
            ->allow(SystemRole::GUEST(), $resource)
            ->allow(SystemRole::LOGGED(), $resource);
    }
}
