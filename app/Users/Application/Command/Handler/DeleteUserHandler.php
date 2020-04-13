<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command\Handler;

use League\Event\EmitterInterface;
use App\Backoffice\Users\Domain\UserRepository;
use App\Backoffice\Users\Application\Command\DeleteUser;

class DeleteUserHandler
{
    private $users;

    private $emitter;

    public function __construct(UserRepository $users, EmitterInterface $emitter)
    {
        $this->users = $users;
        $this->emitter = $emitter;
    }

    public function __invoke(DeleteUser $command): void
    {
        $user = $this->users->find($command->id());

        $this->users->delete($user);
    }
}
