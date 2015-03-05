<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prelacion
 *
 * @ORM\Table(name="prelacion")
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\PrelacionRepository")
 */
class Prelacion
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
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios", cascade={"persist"})
     */
    private $asignatura;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios", cascade={"persist"})
     */
    private $prelacion;
    
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
     * Set asignatura
     *
     * @param \OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios $asignatura
     * @return Prelacion
     */
    public function setAsignatura(\OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios $asignatura = null)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Set prelacion
     *
     * @param \OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios $prelacion
     * @return Prelacion
     */
    public function setPrelacion(\OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios $prelacion = null)
    {
        $this->prelacion = $prelacion;

        return $this;
    }

    /**
     * Get prelacion
     *
     * @return \OpenSkool\AdminBundle\Entity\AsignaturaEtapaPlanEstudios 
     */
    public function getPrelacion()
    {
        return $this->prelacion;
    }
    
    public function __toString() {
      return sprintf("%s -> %s", $this->getAsignatura(), $this->getPrelacion());
    }
}
