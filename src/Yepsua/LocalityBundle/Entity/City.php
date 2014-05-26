<?php

namespace Yepsua\LocalityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="Yepsua\LocalityBundle\Entity\Repository\CityRepository")
 */
class City extends BaseAttribute
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
     * @ORM\ManyToOne(targetEntity="Yepsua\LocalityBundle\Entity\Locality", cascade={"persist"}, inversedBy="cities")
     * @ORM\JoinColumn(name="locality_id", referencedColumnName="id")
     */
    protected $locality;

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
     * Set locality
     *
     * @param \Yepsua\LocalityBundle\Entity\Locality $locality
     * @return City
     */
    public function setLocality(\Yepsua\LocalityBundle\Entity\Locality $locality = null)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return \Yepsua\LocalityBundle\Entity\Locality 
     */
    public function getLocality()
    {
        return $this->locality;
    }
}
