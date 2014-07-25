<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PensumAsignatura
 *
 * @ORM\Table(name="pensum_asignatura")
 * @ORM\Entity
 */
class PensumAsignatura
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
     * @ORM\Column(name="codigo_curricular", type="string", length=16)
     */
    private $codigoCurricular;

    /**
     * @var boolean
     *
     * @ORM\Column(name="electiva", type="boolean")
     */
    private $electiva;

    /**
     * @var integer
     *
     * @ORM\Column(name="unidades_credito", type="smallint")
     */
    private $unidadesCredito;

    /**
     * @var integer
     *
     * @ORM\Column(name="horas_teoricas", type="smallint")
     */
    private $horasTeoricas;

    /**
     * @var integer
     *
     * @ORM\Column(name="horas_practicas", type="smallint")
     */
    private $horasPracticas;
    
    /**
     * @ORM\ManyToOne(targetEntity="Yepsua\LOVBundle\Entity\LOV")
     */
    private $tipoUnidadCurricular;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Pensum", cascade={"persist"})
     */
    private $pensum;
    
    /**
     * @ORM\ManyToOne(targetEntity="OpenSkool\AdminBundle\Entity\Asignatura", cascade={"persist"})
     */
    private $asignatura;
    
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
     * @return PensumAsignatura
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
     * Set codigoCurricular
     *
     * @param string $codigoCurricular
     * @return PensumAsignatura
     */
    public function setCodigoCurricular($codigoCurricular)
    {
        $this->codigoCurricular = $codigoCurricular;

        return $this;
    }

    /**
     * Get codigoCurricular
     *
     * @return string 
     */
    public function getCodigoCurricular()
    {
        return $this->codigoCurricular;
    }

    /**
     * Set electiva
     *
     * @param boolean $electiva
     * @return PensumAsignatura
     */
    public function setElectiva($electiva)
    {
        $this->electiva = $electiva;

        return $this;
    }

    /**
     * Get electiva
     *
     * @return boolean 
     */
    public function getElectiva()
    {
        return $this->electiva;
    }
    
    /**
     * Get electiva
     *
     * @return boolean 
     */
    public function isElectiva()
    {
        return $this->getElectiva();
    }

    /**
     * Set unidadesCredito
     *
     * @param integer $unidadesCredito
     * @return PensumAsignatura
     */
    public function setUnidadesCredito($unidadesCredito)
    {
        $this->unidadesCredito = $unidadesCredito;

        return $this;
    }

    /**
     * Get unidadesCredito
     *
     * @return integer 
     */
    public function getUnidadesCredito()
    {
        return $this->unidadesCredito;
    }

    /**
     * Set horasTeoricas
     *
     * @param integer $horasTeoricas
     * @return PensumAsignatura
     */
    public function setHorasTeoricas($horasTeoricas)
    {
        $this->horasTeoricas = $horasTeoricas;

        return $this;
    }

    /**
     * Get horasTeoricas
     *
     * @return integer 
     */
    public function getHorasTeoricas()
    {
        return $this->horasTeoricas;
    }

    /**
     * Set horasPracticas
     *
     * @param integer $horasPracticas
     * @return PensumAsignatura
     */
    public function setHorasPracticas($horasPracticas)
    {
        $this->horasPracticas = $horasPracticas;

        return $this;
    }

    /**
     * Get horasPracticas
     *
     * @return integer 
     */
    public function getHorasPracticas()
    {
        return $this->horasPracticas;
    }

    /**
     * Set tipoUnidadCurricular
     *
     * @param \Yepsua\LOVBundle\Entity\LOV $tipoUnidadCurricular
     * @return PensumAsignatura
     */
    public function setTipoUnidadCurricular(\Yepsua\LOVBundle\Entity\LOV $tipoUnidadCurricular = null)
    {
        $this->tipoUnidadCurricular = $tipoUnidadCurricular;

        return $this;
    }

    /**
     * Get tipoUnidadCurricular
     *
     * @return \Yepsua\LOVBundle\Entity\LOV 
     */
    public function getTipoUnidadCurricular()
    {
        return $this->tipoUnidadCurricular;
    }

    /**
     * Set pensum
     *
     * @param \OpenSkool\AdminBundle\Entity\Pensum $pensum
     * @return PensumAsignatura
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

    /**
     * Set asignatura
     *
     * @param \OpenSkool\AdminBundle\Entity\Asignatura $asignatura
     * @return PensumAsignatura
     */
    public function setAsignatura(\OpenSkool\AdminBundle\Entity\Asignatura $asignatura = null)
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    /**
     * Get asignatura
     *
     * @return \OpenSkool\AdminBundle\Entity\Asignatura 
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }
    
    public function __toString() {
      return sprintf('%s-%s',$this->getPensum(),$this->getAsignatura());
    }
}
