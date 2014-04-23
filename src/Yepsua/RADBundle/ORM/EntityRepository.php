<?php

/*
 * This file is part of the YepsuaRADBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\RADBundle\ORM;
use Yepsua\CommonsBundle\Persistence\Dao as DAO;

use Doctrine\ORM\EntityRepository as BaseEntityRepository;

/**
 * EntityRepository
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class EntityRepository extends BaseEntityRepository{
    
    const QUERY_ENTITY_NAME = 'undefined';
    
    protected function getLefJoinsAlias(){
        return array();
    }
    
    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
    }
    
    protected function buildQuery($withJoins = true){
      $query =  DAO::buildQuery($this,static::QUERY_ENTITY_NAME);
      if($withJoins){
        foreach ($this->getLefJoinsAlias() as $key => $value) {
          $query = $query->leftJoin($key, $value);
        }
      }
      return $query;
    }
    
    public function getCount(){
        return DAO::count($this);
    }
    
    public function isDataNotFound()
    {
        $cInstituo = $this->getCount();
        return $cInstituo <= 0;
    }
    
    /**
     * Gets the repository for an entity class.
     *
     * @param string $entityName The name of the entity.
     *
     * @return \Doctrine\ORM\EntityRepository The repository class.
     */
    protected function getRepository($repoId){
        return $this->getEntityManager()->getRepository($repoId);
    }
    
}