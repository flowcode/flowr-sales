<?php

namespace Flower\SalesBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
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
     * @var boolean
     *
     * @ORM\Column(name="stock_modifier", type="boolean")
     * @Groups({"public_api"})
     */
    protected $stockModifier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="invoiceable", type="boolean")
     * @Groups({"public_api"})
     */
    protected $invoiceable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="saleDeleted", type="boolean")
     * @Groups({"public_api"})
     */
    protected $saleDeleted;

    /**
     * @ORM\OneToMany(targetEntity="\Flower\ModelBundle\Entity\Sales\Sale", mappedBy="status")
     */
    protected $sales;

    /**
     * @ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Sales\SaleStatus", inversedBy="following")
     * @JoinColumn(name="sale_status_id", referencedColumnName="id")
     */
    protected $previous;

    /**
     * @OneToMany(targetEntity="\Flower\ModelBundle\Entity\Sales\SaleStatus", mappedBy="previous")
     */
    protected $following;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->saleDeleted = false;
        $this->invoiceable = false;
        $this->following = new ArrayCollection();
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

    /**
     * Set saleDeleted
     *
     * @param boolean $saleDeleted
     * @return SaleStatus
     */
    public function setSaleDeleted($saleDeleted)
    {
        $this->saleDeleted = $saleDeleted;

        return $this;
    }

    /**
     * Get saleDeleted
     *
     * @return boolean
     */
    public function getSaleDeleted()
    {
        return $this->saleDeleted;
    }

    /**
     * @return boolean
     */
    public function isInvoiceable()
    {
        return $this->invoiceable;
    }

    /**
     * @param boolean $invoiceable
     */
    public function setInvoiceable($invoiceable)
    {
        $this->invoiceable = $invoiceable;
    }

    /**
     * @return boolean
     */
    public function isStockModifier()
    {
        return $this->stockModifier;
    }

    /**
     * @param boolean $stockModifier
     */
    public function setStockModifier($stockModifier)
    {
        $this->stockModifier = $stockModifier;
    }

    /**
     * @return mixed
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param mixed $previous
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
    }

    /**
     * @return mixed
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param mixed $following
     */
    public function setFollowing($following)
    {
        $this->following = $following;
    }



}
