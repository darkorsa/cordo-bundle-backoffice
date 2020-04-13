<?php

use App\Backoffice\Users\Application\Command\DeleteUser;
use App\Backoffice\Users\Application\Command\UpdateUser;
use App\Backoffice\Users\Application\Command\CreateNewUser;
use App\Backoffice\Users\Application\Command\SendUserWelcomeMessage;
use App\Backoffice\Users\Application\Command\Handler\DeleteUserHandler;
use App\Backoffice\Users\Application\Command\Handler\UpdateUserHandler;
use App\Backoffice\Users\Application\Command\Handler\CreateNewUserHandler;
use App\Backoffice\Users\Application\Command\Handler\SendUserWelcomeMessageHandler;

return [
    CreateNewUser::class            => CreateNewUserHandler::class,
    UpdateUser::class               => UpdateUserHandler::class,
    DeleteUser::class               => DeleteUserHandler::class,
    SendUserWelcomeMessage::class   => SendUserWelcomeMessageHandler::class,
];
