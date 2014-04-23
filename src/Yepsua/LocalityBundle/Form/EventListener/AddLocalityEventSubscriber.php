<?php

namespace Yepsua\LocalityBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddLocalityEventSubscriber implements EventSubscriberInterface
{
    private $localityId;
    private $options;
    
    function __construct($groupName = null, $options = array()) {
      $this->localityId = $groupName;
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
        
        $accessor    = PropertyAccess::createPropertyAccessor();
        
        $city = $accessor->getValue($data, 'city');
        
        $options = array();
        $type = 'choice';
        if($city !== null){
          $this->localityId = $city->getLocality()->getId();
          $options['class'] = 'YepsuaLocalityBundle:Locality';
          $type = 'entity';
          $options['query_builder'] = function(EntityRepository $er) {
            return $er->_findAllByCountry($this->localityId);
          };
        }
        
        $form->add('locality',$type, array_merge($options,$this->options));
    }
    
    
}