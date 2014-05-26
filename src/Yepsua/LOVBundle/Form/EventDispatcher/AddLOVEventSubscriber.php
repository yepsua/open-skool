<?php

namespace Yepsua\LOVBundle\Form\EventDispatcher;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;

class AddLOVEventSubscriber implements EventSubscriberInterface
{
    private $groupName;
    private $options;
    
    function __construct($groupName, $options = array()) {
      $this->groupName = $groupName;
      $this->options = $options;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        /*$accessor    = PropertyAccess::createPropertyAccessor();
        
        $test = $accessor->getValue($data, 'addressType.id');
        
        echo $test;*/

        $form->add('addressType', 'entity' , 
          array_merge(
            array(
              'class' => 'YepsuaLOVBundle:LOV',
              'query_builder' => function(EntityRepository $er) {
                   return $er->_findAllByGroupName($this->groupName);
            }), 
            $this->options
          )
        );
    }
    
    
}