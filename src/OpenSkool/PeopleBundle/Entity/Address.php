<?php

namespace OpenSkool\PeopleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenSkool\CoreBundle\Entity\Address as BaseAddress;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="OpenSkool\PeopleBundle\Entity\AddressRepository")
 */
class Address extends BaseAddress
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
     * @ORM\ManyToMany(targetEntity="OpenSkool\PeopleBundle\Entity\Person", inversedBy="addresses")
     * @ORM\JoinTable(name="person_address",
     *      joinColumns={@ORM\JoinColumn(name="address_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="person_id", referencedColumnName="id")}
     *      )
     */
    private $owners;
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->owners = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add owners
     *
     * @param \OpenSkool\PeopleBundle\Entity\Person $owners
     * @return Address
     */
    public function addOwner(\OpenSkool\PeopleBundle\Entity\Person $owners)
    {
        $this->owners[] = $owners;

        return $this;
    }

    /**
     * Remove owners
     *
     * @param \OpenSkool\PeopleBundle\Entity\Person $owners
     */
    public function removeOwner(\OpenSkool\PeopleBundle\Entity\Person $owners)
    {
        $this->owners->removeElement($owners);
    }

    /**
     * Get owners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * Set city
     *
     * @param \Yepsua\LocalityBundle\Entity\City $city
     * @return Address
     */
    public function setCity(\Yepsua\LocalityBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Yepsua\LocalityBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    public function getLocality(){
        $locality = null;
        
        if($this->getCity() !== null){
          $locality = $this->city->getLocality();
        }
        
        return $locality;
    }
    
    public function getCountry(){
        $country = null;
        $locality = $this->getLocality();
        
        if($locality !== null){
          $country = $locality->getCountry();
        }
        
        return $country;
    }
    
}
