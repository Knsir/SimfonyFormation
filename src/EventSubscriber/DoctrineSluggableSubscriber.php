<?php 

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Service\Slugify;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

class DoctrineSluggableSubscriber implements EventSubscriber
{
    private $slugifyService; 
    private $logger;

    public function __construct(Slugify $slugifyService, LoggerInterface $logger){
        $this->slugifyService = $slugifyService;
        $this->logger = $logger;
    }
    
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }


    public function preUpdate(LifecycleEventArgs $args){
        $entity= $args->getObject();
        $em= $args->getObjectManager();

        if($entity instanceof Product){
            $this->logger->alert("test");
            
            if(array_key_exists('name', $args->getEntityChangeSet())){
                $entity->setSlug($this->slugifyService->slugify($entity->getName()));
        }
        }
    }

    public function prePersist(LifecycleEventArgs $args){

        $this->slugifySubscriber($args);
    }

    protected function slugifySubscriber(LifecycleEventArgs $value)
    {
        $entity = $value->getObject();
        if(!$entity instanceof Product){
        
        }


    }

}