<?php

namespace Utopia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Utopia\CoreBundle\Entity\Foto;

/**
 * Persona
 *
 * @ORM\Table(name="persona_tabla_uno")
 * @ORM\Entity(repositoryClass="Utopia\CoreBundle\Entity\PersonaRepository")
 */
class Persona
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var integer
     *
     * @ORM\Column(name="edad", type="smallint")
     */
    private $edad;

    /**
     * @var string
     * @ORM\OneToOne(targetEntity="Utopia\CoreBundle\Entity\Foto", cascade={"persist"})
     */
    private $foto;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $fotoFile;


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
     * Set nombre
     *
     * @param string $nombre
     * @return Persona
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
     * Set apellido
     *
     * @param string $apellido
     * @return Persona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set edad
     *
     * @param integer $edad
     * @return Persona
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;

        return $this;
    }

    /**
     * Get edad
     *
     * @return integer 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set foto
     *
     * @param \Utopia\CoreBundle\Entity\Foto $foto
     * @return Persona
     */
    public function setFoto(\Utopia\CoreBundle\Entity\Foto $foto = null)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return \Utopia\CoreBundle\Entity\Foto 
     */
    public function getFoto()
    {
        return $this->foto;
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFotoFile()
    {
        return $this->fotoFile;
    }
    
    /**
     * Sets file.
     * 
     * @param UploadedFile $file
     */
    public function setFotoFile(UploadedFile $file = null)
    {
        if($file !== null){
            if($this->getFoto() === null){
              $this->setFoto(new Foto($file));
            }else{
              $this->getFoto()->setFile($file);
            }
            $this->getFoto()->write($file);
        }
    }
}
