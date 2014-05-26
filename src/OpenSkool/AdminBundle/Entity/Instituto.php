<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Instituto
 *
 * @ORM\Table(name="instituto")
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\InstitutoRepository")
 */
class Instituto
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
     * @ORM\Column(name="codigo", type="string", length=10, unique=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="acronimo", type="string", length=24, nullable=true)
     */
    private $acronimo;
    
    /**
     * @var string
     * @ORM\OneToOne(targetEntity="OpenSkool\AdminBundle\Entity\Imagen", cascade={"persist"})
     */
    private $imagen;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $imagenFile;
    
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
     * @return Instituto
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
     * @return Instituto
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Instituto
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
     * Set direccion
     *
     * @param string $direccion
     * @return Instituto
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set imagen
     *
     * @param \OpenSkool\AdminBundle\Entity\Imagen $imagen
     * @return Instituto
     */
    public function setImagen(\OpenSkool\AdminBundle\Entity\Imagen $imagen = null)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return \OpenSkool\AdminBundle\Entity\Imagen 
     */
    public function getImagen()
    {
        return $this->imagen;
    }
    
    /**
     * Set acronimo
     *
     * @param string $acronimo
     * @return Instituto
     */
    public function setAcronimo($acronimo)
    {
        $this->acronimo = $acronimo;

        return $this;
    }

    /**
     * Get acronimo
     *
     * @return string 
     */
    public function getAcronimo()
    {
        return $this->acronimo;
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getImagenFile()
    {
        return $this->imagenFile;
    }
    
    /**
     * Sets file.
     * 
     * @param UploadedFile $file
     */
    public function setImagenFile(UploadedFile $file = null)
    {
        if($file !== null){
            if($this->getImagen() === null){
              $this->setImagen(new Imagen($file));
            }else{
              $this->getImagen()->setFile($file);
            }
            $this->getImagen()->setTipo(Imagen::TIPO_IMAGEN_INSTITUO);
            $this->getImagen()->setName($this->getNombreImagen($file));
            $this->getImagen()->write($file);
        }
    }
    
    protected function getNombreImagen(UploadedFile $file){
      $fileName = ($this->acronimo !== null) 
                      ? $this->acronimo
                      : $this->getNombre();
      $fileName = sprintf('%s.%s',$fileName,$file->getClientOriginalExtension());
      return $fileName;
    }
    
        
    /**
     * Get to String
     * @return type
     */
    public function __toString() {
        return $this->getNombre();
    }
}
