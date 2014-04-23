<?php

namespace Yepsua\LOVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LOVGroup
 *
 * @ORM\Table(name="list_of_value_group")
 * @ORM\Entity
 */
class LOVGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="string", length=128)
     */
    private $groupName;


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
     * Set groupName
     *
     * @param string $groupName
     * @return LOVGroup
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string 
     */
    public function getGroupName()
    {
        return $this->groupName;
    }
    
    public function __toString(){
        return $this->getGroupName();
    }
}
