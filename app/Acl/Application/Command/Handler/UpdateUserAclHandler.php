<?php

declare(strict_types=1);

namespace App\Backoffice\Acl\Application\Command\Handler;

use App\Backoffice\Acl\Domain\AclRepository;
use League\Event\EmitterInterface;
use App\Backoffice\Acl\Application\Command\UpdateUserAcl;

class UpdateUserAclHandler
{
    private $acl;

    private $emitter;

    public function __construct(AclRepository $acl, EmitterInterface $emitter)
    {
        $this->acl = $acl;
        $this->emitter = $emitter;
    }

    public function __invoke(UpdateUserAcl $command): void
    {
        $acl = $this->acl->find($command->id());

        $acl->update(
            $command->privileges(),
            $command->updatedAt()
        );

        $this->acl->update($acl);
    }
}
