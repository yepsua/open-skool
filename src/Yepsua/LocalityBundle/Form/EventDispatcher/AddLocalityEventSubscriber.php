<?php

namespace Yepsua\LocalityBundle\Form\EventDispatcher;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddLocalityEventSubscriber implements EventSubscriberInterface
{
    private $countryId;
    private $localityId;
    private $cityId;
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
        
        $options1 = array('empty_value' => 'form.choice.empty.locality','class' => 'YepsuaLocalityBundle:Locality');
        $options2 = array(
          'class' => 'YepsuaLocalityBundle:Country', 
          'empty_value' => 'form.choice.empty.country', 
          'query_builder'=> function(EntityRepository $er) {
            return $er->createQueryBuilder('country');
        });
        
        $options3 = array('empty_value' => 'form.choice.empty.city', 'class' => 'YepsuaLocalityBundle:City');

        if($city !== null){

          $this->countryId = $city->getLocality()->getCountry()->getId();
          $options1['query_builder'] = function(EntityRepository $er) {
            return $er->_findAllByCountry($this->countryId);
          };
           
          $this->localityId = $city->getLocality()->getId();

          $options3['query_builder'] = function(EntityRepository $er) {
            return $er->_findAllByLocality($this->localityId);
          };
          
        }
        
        $form->add('country','entity', array_merge($options2,$this->options));
        $form->add('locality','entity', array_merge($options1,$this->options));
        $form->add('city','entity', array_merge($options3,$this->options));
    }
}