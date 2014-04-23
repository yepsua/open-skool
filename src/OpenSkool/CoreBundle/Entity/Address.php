<?php

namespace OpenSkool\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\MappedSuperclass
 */
class Address
{
    /**
     * @var string
     *
     * @ORM\Column(name="line_one", type="string", length=255)
     */
    protected $lineOne;

    /**
     * @var string
     *
     * @ORM\Column(name="line_two", type="string", length=255)
     */
    protected $lineTwo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Yepsua\LOVBundle\Entity\LOV")
     */
    protected $addressType;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=16)
     */
    protected $zipcode;
    
    /**
     * @ORM\ManyToOne(targetEntity="Yepsua\LocalityBundle\Entity\City")
     */
    protected $city;


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
     * Set lineOne
     *
     * @param string $lineOne
     * @return Address
     */
    public function setLineOne($lineOne)
    {
        $this->lineOne = $lineOne;

        return $this;
    }

    /**
     * Get lineOne
     *
     * @return string 
     */
    public function getLineOne()
    {
        return $this->lineOne;
    }

    /**
     * Set lineTwo
     *
     * @param string $lineTwo
     * @return Address
     */
    public function setLineTwo($lineTwo)
    {
        $this->lineTwo = $lineTwo;

        return $this;
    }

    /**
     * Get lineTwo
     *
     * @return string 
     */
    public function getLineTwo()
    {
        return $this->lineTwo;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return Address
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set addressType
     *
     * @param \Yepsua\LOVBundle\Entity\LOV $addressType
     * @return Address
     */
    public function setAddressType(\Yepsua\LOVBundle\Entity\LOV $addressType = null)
    {
        $this->addressType = $addressType;

        return $this;
    }

    /**
     * Get addressType
     *
     * @return \Yepsua\LOVBundle\Entity\LOV 
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * Set city
     *
     * @param \Yepsua\LocalityBundle\Entity\City $city
     * @return Address
     */
    public function setCity(\Yepsua\LocalityBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Yepsua\LocalityBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
}
