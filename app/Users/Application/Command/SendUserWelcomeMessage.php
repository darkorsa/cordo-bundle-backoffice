<?php

declare(strict_types=1);

namespace App\Backoffice\Users\Application\Command;

use Cordo\Core\Application\Queue\QueueMessage;

class SendUserWelcomeMessage extends QueueMessage
{
    public string $email;

    public string $locale;
}
