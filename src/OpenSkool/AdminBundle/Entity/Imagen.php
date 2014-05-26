<?php

namespace OpenSkool\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yepsua\CommonsBundle\Entity\Document;

/**
 * @ORM\Table(name="imagen_metadata")
 * @ORM\Entity(repositoryClass="OpenSkool\AdminBundle\Entity\Repository\ImagenRepository")
 */
class Imagen extends Document
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
  
    const TIPO_IMAGEN_INSTITUO = 'IMAGEN_INSTITUTO';
    
    public static function GET_DIRECTORIO_IMAGEN(){
        return array(
            'IMAGEN_INSTITUTO' => 'uploads/images/instituto',
        );
    }
    
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
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=64)
     */
    protected $tipo;
    
    public function getWebPath(){
        return "/open-skool/web";
    }
    
    public function getUploadDir(){
      $dirs = self::GET_DIRECTORIO_IMAGEN();
      return $dirs['IMAGEN_INSTITUTO'];
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
