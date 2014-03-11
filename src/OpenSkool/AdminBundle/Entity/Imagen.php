<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yepsua\CommonsBundle\Entity\Document;

/**
 * @ORM\Table(name="imagen_metadata")
 * @ORM\Entity()
 */
class Imagen extends Document
{
    const TIPO_IMAGEN_INSTITUO = 'IMAGEN_INSTITUO';
    
    public static $DIRECTOTIO_IMAGEN = array(
        'IMAGEN_INSTITUO' => 'uploads/images/instituto',
    );
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=64)
     */
    private $tipo;
    
    public function getWebPath(){
        return "/open-skool/web";
    }
    
    public function getUploadDir(){
      $dirs = self::$DIRECTOTIO_IMAGEN;
      return $dirs[$this->getTipo()];
    }
    

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Imagen
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
