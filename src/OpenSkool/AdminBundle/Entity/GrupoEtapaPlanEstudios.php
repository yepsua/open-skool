<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoEtapaPlanEstudios
 *
 * @ORM\Table(name="grupo_etapa_plan_estudios")
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\GrupoEtapaPlanEstudiosRepository")
 */
class GrupoEtapaPlanEstudios
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
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Grupo", cascade={"persist"})
     */
    private $grupo;
    
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
     * Set grupo
     *
     * @param \OpenSkool\AdminBundle\Entity\Grupo $grupo
     * @return GrupoEtapaPlanEstudios
     */
    public function setGrupo(\OpenSkool\AdminBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \OpenSkool\AdminBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set etapaPlanEstudios
     *
     * @param \OpenSkool\AdminBundle\Entity\EtapaPlanEstudios $etapaPlanEstudios
     * @return GrupoEtapaPlanEstudios
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
