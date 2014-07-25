<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pensum
 *
 * @ORM\Table(name="pensum")
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\PensumRepository")
 */
class Pensum
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
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Instituto", cascade={"persist"})
     */
    private $instituto;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Carrera", cascade={"persist"})
     */
    private $carrera;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Pensum", cascade={"persist"})
     */
    private $pensumPadre;
    
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
     * Set instituto
     *
     * @param \OpenSkool\AdminBundle\Entity\Instituto $instituto
     * @return Pensum
     */
    public function setInstituto(\OpenSkool\AdminBundle\Entity\Instituto $instituto = null)
    {
        $this->instituto = $instituto;

        return $this;
    }

    /**
     * Get instituto
     *
     * @return \OpenSkool\AdminBundle\Entity\Instituto 
     */
    public function getInstituto()
    {
        return $this->instituto;
    }

    /**
     * Set carrera
     *
     * @param \OpenSkool\AdminBundle\Entity\Carrera $carrera
     * @return Pensum
     */
    public function setCarrera(\OpenSkool\AdminBundle\Entity\Carrera $carrera = null)
    {
        $this->carrera = $carrera;

        return $this;
    }

    /**
     * Get carrera
     *
     * @return \OpenSkool\AdminBundle\Entity\Carrera 
     */
    public function getCarrera()
    {
        return $this->carrera;
    }

    /**
     * Set pensumPadre
     *
     * @param \OpenSkool\AdminBundle\Entity\Pensum $pensumPadre
     * @return Pensum
     */
    public function setPensumPadre(\OpenSkool\AdminBundle\Entity\Pensum $pensumPadre = null)
    {
        $this->pensumPadre = $pensumPadre;

        return $this;
    }

    /**
     * Get pensumPadre
     *
     * @return \OpenSkool\AdminBundle\Entity\Pensum 
     */
    public function getPensumPadre()
    {
        return $this->pensumPadre;
    }
    
    public function __toString() {
      return sprintf('%s-%s',$this->instituto, $this->carrera);
    }
}
