<?php

namespace Yepsua\LocalityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country extends BaseAttribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Yepsua\LocalityBundle\Entity\Locality", mappedBy="country")
     */
    protected $localities;


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
        $this->localities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add localities
     *
     * @param \Yepsua\LocalityBundle\Entity\Locality $localities
     * @return Country
     */
    public function addLocality(\Yepsua\LocalityBundle\Entity\Locality $localities)
    {
        $this->localities[] = $localities;

        return $this;
    }

    /**
     * Remove localities
     *
     * @param \Yepsua\LocalityBundle\Entity\Locality $localities
     */
    public function removeLocality(\Yepsua\LocalityBundle\Entity\Locality $localities)
    {
        $this->localities->removeElement($localities);
    }

    /**
     * Get localities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocalities()
    {
        return $this->localities;
    }
}
