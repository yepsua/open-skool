<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignatura
 *
 * @ORM\Table(name="asignatura")
 * @ORM\Entity
 */
class Asignatura
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
     * @ORM\Column(name="codigo", type="string", length=16, unique=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=128)
     */
    private $nombre;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Asignatura", cascade={"persist"})
     */
    private $asignaturaPadre;


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
     * @return Asignatura
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
     * @return Asignatura
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
     * Set asignaturaPadre
     *
     * @param \OpenSkool\AdminBundle\Entity\Asignatura $asignaturaPadre
     * @return Asignatura
     */
    public function setAsignaturaPadre(\OpenSkool\AdminBundle\Entity\Asignatura $asignaturaPadre = null)
    {
        $this->asignaturaPadre = $asignaturaPadre;

        return $this;
    }

    /**
     * Get asignaturaPadre
     *
     * @return \OpenSkool\AdminBundle\Entity\Asignatura 
     */
    public function getAsignaturaPadre()
    {
        return $this->asignaturaPadre;
    }
    
    /**
     * Get to String
     * @return type
     */
    public function __toString() {
      $codigo = $this->getCodigo();
      if($codigo != null){
        $value = sprintf('%s (%s)',$this->getNombre(),$codigo);
      }else{
        $value = $this->getNombre();
      }
      return $value;
    }
}
