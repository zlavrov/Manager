<?php

namespace App\Model\In\User;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractUserIn
{
    public $email;

    public $password;
}
