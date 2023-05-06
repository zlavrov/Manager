<?php

namespace App\EventListener;

use DateTime;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TimeListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new DateTime('now'));
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTime('now'));
        }
    }
}
