<?php

namespace Yepsua\SecurityBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="security_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
     protected $id;
     
    /**
     * @ORM\ManyToMany(targetEntity="Yepsua\SecurityBundle\Entity\Role")
     * @ORM\JoinTable(name="security_group_security_role",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $related_roles;
     
     
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __construct($name = null, $roles = array())
    {
        $this->name = $name;
        $this->roles = $roles;
    }
    
    public function __toString() {
        return $this->getName();
    }

    /**
     * Add related_roles
     *
     * @param \Yepsua\SecurityBundle\Entity\Role $relatedRoles
     * @return Group
     */
    public function addRelatedRole(\Yepsua\SecurityBundle\Entity\Role $relatedRoles)
    {
        $this->related_roles[] = $relatedRoles;
        $this->addRole($relatedRoles->getName());
        return $this;
    }

    /**
     * Remove related_roles
     *
     * @param \Yepsua\SecurityBundle\Entity\Role $relatedRoles
     */
    public function removeRelatedRole(\Yepsua\SecurityBundle\Entity\Role $relatedRoles)
    {
        $this->related_roles->removeElement($relatedRoles);
        $this->removeRole($relatedRoles->getName());
    }

    /**
     * Get related_roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelatedRoles()
    {
        return $this->related_roles;
    }
    
    /**
     * Synchronize all roles with the related roles.
     */
    public function synchronizeRoles(){
      foreach($this->getRoles() as $role){
        $this->removeRole($role);
      }
      foreach($this->getRelatedRoles() as $role){
        $this->addRole($role->getName());
      }
    }
}
