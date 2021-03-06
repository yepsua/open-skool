<?php

namespace Yepsua\SecurityBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="security_user")
 */
class User extends BaseUser
{    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function __construct()
    {
        parent::__construct();
        $this->setEnabled(true);
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
     * @ORM\ManyToMany(targetEntity="Yepsua\SecurityBundle\Entity\Group")
     * @ORM\JoinTable(name="security_user_security_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToOne(targetEntity="OpenSkool\PeopleBundle\Entity\Person", mappedBy="user", cascade={"persist"})
     */
    protected $userDetail;

    
    public function getExpiresAt(){
        return $this->expiresAt;
    }
    
    public function getCredentialsExpireAt(){
        return $this->credentialsExpireAt;
    }

    /**
     * Set userDetail
     *
     * @param \OpenSkool\PeopleBundle\Entity\Person $userDetail
     * @return User
     */
    public function setUserDetail(\OpenSkool\PeopleBundle\Entity\Person $userDetail = null)
    {
        $this->userDetail = $userDetail;

        return $this;
    }

    /**
     * Get userDetail
     *
     * @return \OpenSkool\PeopleBundle\Entity\Person 
     */
    public function getUserDetail()
    {
        return $this->userDetail;
    }
}
