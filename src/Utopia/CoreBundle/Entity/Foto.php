<?php

namespace Utopia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yepsua\CommonsBundle\Entity\Document;

/**
 * @ORM\Table(name="foto")
 * @ORM\Entity()
 */
class Foto extends Document
{ 
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function getWebPath(){
        return "/open-skool/web";
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }
}