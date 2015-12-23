<?php

namespace Flower\SalesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
/**
 * SaleStatus
 *
 */
class SaleStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"public_api"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"public_api"})
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="saleModificable", type="boolean")
     * @Groups({"public_api"})
     */
    protected $saleModificable;

    /**
     * @ORM\OneToMany(targetEntity="\Flower\ModelBundle\Entity\Sales\Sale", mappedBy="status")
     */
    protected $sales;
         
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return SaleStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set saleModificable
     *
     * @param boolean $saleModificable
     * @return SaleStatus
     */
    public function setSaleModificable($saleModificable)
    {
        $this->saleModificable = $saleModificable;

        return $this;
    }

    /**
     * Get saleModificable
     *
     * @return boolean 
     */
    public function getSaleModificable()
    {
        return $this->saleModificable;
    }

    /**
     * Add sales
     *
     * @param \Flower\ModelBundle\Entity\Sales\Sale $sales
     * @return SaleStatus
     */
    public function addSale(\Flower\ModelBundle\Entity\Sales\Sale $sales)
    {
        $this->sales[] = $sales;

        return $this;
    }

    /**
     * Remove sales
     *
     * @param \Flower\ModelBundle\Entity\Sales\Sale $sales
     */
    public function removeSale(\Flower\ModelBundle\Entity\Sales\Sale $sales)
    {
        $this->sales->removeElement($sales);
    }

    /**
     * Get sales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSales()
    {
        return $this->sales;
    }

        
    public function __toString()
    {
        return $this->name;
    }
}
