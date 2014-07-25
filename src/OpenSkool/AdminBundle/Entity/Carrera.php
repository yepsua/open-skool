<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carrera
 *
 * @ORM\Table(name="carrera",uniqueConstraints={
 *   @ORM\UniqueConstraint(name="carrera_uid",
 *     columns={"titulo_id", "mencion_id"})
 * })
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\CarreraRepository")
 */
class Carrera
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
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Titulo", cascade={"persist"})
     */
    private $titulo;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Mencion", cascade={"persist"})
     */
    private $mencion;
    

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
     * @return Carrera
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
     * Set titulo
     *
     * @param \OpenSkool\AdminBundle\Entity\Titulo $titulo
     * @return Carrera
     */
    public function setTitulo(\OpenSkool\AdminBundle\Entity\Titulo $titulo = null)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return \OpenSkool\AdminBundle\Entity\Titulo 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set mencion
     *
     * @param \OpenSkool\AdminBundle\Entity\Mencion $mencion
     * @return Carrera
     */
    public function setMencion(\OpenSkool\AdminBundle\Entity\Mencion $mencion = null)
    {
        $this->mencion = $mencion;

        return $this;
    }

    /**
     * Get mencion
     *
     * @return \OpenSkool\AdminBundle\Entity\Mencion 
     */
    public function getMencion()
    {
        return $this->mencion;
    }
    
    /**
     * Get to String
     * @return type
     */
    public function __toString() {
        return sprintf('%s-%s', $this->getTitulo()->getNombre(), $this->getMencion()->getNombre());
    }
    
}
