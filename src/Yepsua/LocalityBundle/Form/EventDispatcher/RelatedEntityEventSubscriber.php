<?php

namespace Yepsua\LocalityBundle\Form\EventDispatcher;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Doctrine\ORM\EntityRepository;

class RelatedEntityEventSubscriber implements EventSubscriberInterface
{
    private static $DEFAULT_LABEL_VALUE = "form.choice.empty.label";
    private $relatedEntities;
    
    public function __construct(){
      $this->relatedEntities = array();
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $this->configureForm($event);
    }
    
    public function preSubmit(FormEvent $event)
    {
        $this->configureForm($event);
    }
    
    protected function configureForm(FormEvent $event){
      $data = $event->getData();
      $form = $event->getForm();
      
      foreach ($this->relatedEntities as $widgetId => $entityOptions){
        $options = $this->getWidgetOptions($widgetId, $entityOptions, $data); 
        $form->add($widgetId,'entity', $options);
      }
    }
    
    protected function getWidgetOptions($widgetId, $options, $data){
      if(!isset($options['query_builder'])){
        $options['query_builder'] = $this->buildQueryBuilderFunction('createQueryBuilder', array($widgetId));
      }else{
        try{
          $options['query_builder'] = $this->buildQueryBuilder($options['query_builder'], $data);
        }catch(\Exception $e){
          unset($options['query_builder']);
        }
      }

      return array_merge($this->getDefaultOptions(), $options);
    }
    
    protected function buildQueryBuilder($options, $data){
      $queryBuilder = $options;
      if(isset($options['method'])){
        $args = array();
        if(isset($options['args'])){
          $args = $this->buildQueryBuilderFunctionArgs($options['args'],$data);
        }
        $queryBuilder = $this->buildQueryBuilderFunction($options['method'],$args);
      }
      return $queryBuilder;
    }
    
    public function buildQueryBuilderFunctionArgs($function_args, $data){
      $el = new ExpressionLanguage();
      $args = array();
      foreach($function_args as $expresion => $arg){
        //try {
          $args[] = $el->evaluate($expresion,array('data' => $data));
        //} catch (\Exception $e) {
          //$args[] = $arg;
        //}
      }
      return $args;
    }
    
    private function buildQueryBuilderFunction($method, $args = array()){
      return function(EntityRepository $er) use ($method, $args) {
        $rMethod = new \ReflectionMethod($er, $method);
        return $rMethod->invokeArgs($er, $args);
      };
    }
    
    public function add($relationId, $options = array()){
      $this->relatedEntities[$relationId] = $options;
    }
    
    public static function getDefaultOptions(){
      return array(
        'placeholder' => static::$DEFAULT_LABEL_VALUE
      );
    }
    
    public function getRelatedEntities() {
      return $this->relatedEntities;
    }
    
    public function setRelatedEntities($relatedEntities) {
      $this->relatedEntities = $relatedEntities;
    }
}