<?php

namespace App\Model\In\Task;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractTaskIn
{
    public $title;

    public $description;

    public $status;

    public $deadline;
}
