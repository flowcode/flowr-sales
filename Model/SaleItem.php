<?php

namespace Flower\SalesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * SaleItem
 */
class SaleItem
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
     * @var integer
     *
     * @ORM\Column(name="units", type="integer")
     * @Groups({"public_api"})
     */
    protected $units;

    /**
     * @var float
     *
     * @ORM\Column(name="unitPrice", type="float")
     * @Groups({"public_api"})
     */
    protected $unitPrice;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Stock\Product", cascade={"persist"})
     * @ORM\JoinColumn(name="product", referencedColumnName="id", nullable=true)
     * @Groups({"public_api"})
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Stock\Service", cascade={"persist"})
     * @ORM\JoinColumn(name="service", referencedColumnName="id", nullable=true)
     * @Groups({"public_api"})
     */
    protected $service;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     * @Groups({"public_api"})
     */
    protected $total;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Sales\Sale", cascade={"persist"})
     * @ORM\JoinColumn(name="sale", referencedColumnName="id")
     */
    protected $sale;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set units
     *
     * @param integer $units
     * @return SaleItem
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units
     *
     * @return integer
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return SaleItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return SaleItem
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set sale
     *
     * @param \Flower\ModelBundle\Entity\Sales\Sale $sale
     * @return SaleItem
     */
    public function setSale(\Flower\ModelBundle\Entity\Sales\Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \Flower\ModelBundle\Entity\Sales\Sale
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set service
     *
     * @param \Flower\ModelBundle\Entity\Stock\service $service
     * @return SaleItem
     */
    public function setService(\Flower\ModelBundle\Entity\Stock\service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Flower\ModelBundle\Entity\Stock\service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set product
     *
     * @param \Flower\ModelBundle\Entity\Stock\Product $product
     * @return SaleItem
     */
    public function setProduct(\Flower\ModelBundle\Entity\Stock\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Flower\ModelBundle\Entity\Stock\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
