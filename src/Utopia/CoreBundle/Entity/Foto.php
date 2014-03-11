<?php

namespace Utopia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Yepsua\CommonsBundle\Entity\Document;

/**
 * @ORM\Table(name="foto")
 * @ORM\Entity()
 */
class Foto extends Document
{
    public function getWebPath(){
        return "/open-skool/web";
    }
}