<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsignaturaEtapaPlanEstudios
 *
 * @ORM\Table(name="asignatura_etapa_plan_estudios", uniqueConstraints={
 *   @ORM\UniqueConstraint(name="asignatura_etapa_plan_estudios_uid", 
 *     columns={"pensumasignatura_id", "etapaplanestudios_id"}
 *   )
 * })
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\AsignaturaEtapaPlanEstudiosRepository")
 */
class AsignaturaEtapaPlanEstudios
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
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\PensumAsignatura", cascade={"persist"})
     */
    private $pensumAsignatura;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\EtapaPlanEstudios", cascade={"persist"})
     */
    private $etapaPlanEstudios;

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
     * Set pensumAsignatura
     *
     * @param \OpenSkool\AdminBundle\Entity\PensumAsignatura $pensumAsignatura
     * @return AsignaturaEtapaPlanEstudios
     */
    public function setPensumAsignatura(\OpenSkool\AdminBundle\Entity\PensumAsignatura $pensumAsignatura = null)
    {
        $this->pensumAsignatura = $pensumAsignatura;

        return $this;
    }

    /**
     * Get pensumAsignatura
     *
     * @return \OpenSkool\AdminBundle\Entity\PensumAsignatura 
     */
    public function getPensumAsignatura()
    {
        return $this->pensumAsignatura;
    }

    /**
     * Set etapaPlanEstudios
     *
     * @param \OpenSkool\AdminBundle\Entity\EtapaPlanEstudios $etapaPlanEstudios
     * @return AsignaturaEtapaPlanEstudios
     */
    public function setEtapaPlanEstudios(\OpenSkool\AdminBundle\Entity\EtapaPlanEstudios $etapaPlanEstudios = null)
    {
        $this->etapaPlanEstudios = $etapaPlanEstudios;

        return $this;
    }

    /**
     * Get etapaPlanEstudios
     *
     * @return \OpenSkool\AdminBundle\Entity\EtapaPlanEstudios 
     */
    public function getEtapaPlanEstudios()
    {
        return $this->etapaPlanEstudios;
    }
}
