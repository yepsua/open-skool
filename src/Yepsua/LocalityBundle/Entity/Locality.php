<?php

namespace Yepsua\LocalityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Locality
 *
 * @ORM\Table(name="locality")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Yepsua\LocalityBundle\Entity\LocalityRepository")
 */
class Locality extends BaseAttribute
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
     * @ORM\OneToMany(targetEntity="Yepsua\LocalityBundle\Entity\City", mappedBy="locality")
     */
    protected $cities;

    /**
     * @ORM\ManyToOne(targetEntity="Yepsua\LocalityBundle\Entity\Country", cascade={"persist"}, inversedBy="localities")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $country;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add cities
     *
     * @param \Yepsua\LocalityBundle\Entity\City $cities
     * @return Locality
     */
    public function addCity(\Yepsua\LocalityBundle\Entity\City $cities)
    {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove cities
     *
     * @param \Yepsua\LocalityBundle\Entity\City $cities
     */
    public function removeCity(\Yepsua\LocalityBundle\Entity\City $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Set country
     *
     * @param \Yepsua\LocalityBundle\Entity\Country $country
     * @return Locality
     */
    public function setCountry(\Yepsua\LocalityBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Yepsua\LocalityBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
