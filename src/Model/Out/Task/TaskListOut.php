<?php

namespace App\Model\Out\Task;

use JMS\Serializer\Annotation as Serializer;

class TaskListOut
{

    public $id;


    public $title;

    public $description;

    public $status;

    public $deadline;
}
