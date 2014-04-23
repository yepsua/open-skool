<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facultad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Facultad
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
     * @ORM\Column(name="codigo", type="string", length=16)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=64)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Instituto", cascade={"persist"})
     */
    private $instituto;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Carrera", cascade={"persist"})
     */
    private $carrera;


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
     * Set codigo
     *
     * @param string $codigo
     * @return Facultad
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Facultad
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set instituto
     *
     * @param \OpenSkool\AdminBundle\Entity\Instituto $instituto
     * @return Facultad
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
     * @return Facultad
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
}
