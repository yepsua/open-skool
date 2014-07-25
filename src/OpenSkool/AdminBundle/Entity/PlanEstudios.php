<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanEstudios
 *
 * @ORM\Table(name="plan_estudios")
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\PlanEstudiosRepository")
 */
class PlanEstudios
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
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Pensum", cascade={"persist"})
     */
    private $pensum;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=16, unique=true)
     */
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

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
     * @return PlanEstudios
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return PlanEstudios
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set pensum
     *
     * @param \OpenSkool\AdminBundle\Entity\Pensum $pensum
     * @return PlanEstudios
     */
    public function setPensum(\OpenSkool\AdminBundle\Entity\Pensum $pensum = null)
    {
        $this->pensum = $pensum;

        return $this;
    }

    /**
     * Get pensum
     *
     * @return \OpenSkool\AdminBundle\Entity\Pensum 
     */
    public function getPensum()
    {
        return $this->pensum;
    }
    
    public function __toString() {
      $planEstudios = "";
      if ($this->pensum !== null){
        $planEstudios = sprintf('%s-%s-(%s)',$this->getPensum(),$this->getDescripcion(),$this->getCodigo());
      }else{
        $planEstudios = sprintf('%s-%s',$this->getDescripcion(),$this->getCodigo());
      }
      return $planEstudios;
    }
}
