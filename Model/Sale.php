<?php

namespace Flower\SalesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
/**
 * Sale
 */
class Sale
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     * @Groups({"public_api"})
     */
    protected $total;

    /**
     * @var float
     *
     * @ORM\Column(name="totalWithTax", type="float")
     * @Groups({"public_api"})
     */
    protected $totalWithTax;

    /**
     * @var float
     *
     * @ORM\Column(name="tax", type="float")
     * @Groups({"public_api"})
     */
    protected $tax;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text", nullable=true)
     * @Groups({"public_api"})
     */
    protected $observations;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Clients\Contact")
     * @ORM\JoinColumn(name="contact", referencedColumnName="id")
     * @Groups({"public_api"})
     */
    protected $contact;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Clients\Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     * @Groups({"public_api"})
     */
    protected $account;
    /**
     * @ManyToOne(targetEntity="\Flower\ModelBundle\Entity\User\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"public_api"})
     * */
    protected $owner;
    /**
     * @ORM\OneToMany(targetEntity="\Flower\ModelBundle\Entity\Sales\SaleItem", mappedBy="sale", cascade={"persist", "remove"})
     * @Groups({"public_api"})
     */
    protected $saleItems;

    /**
     * @ORM\ManyToOne(targetEntity="\Flower\ModelBundle\Entity\Sales\PaymentMethod")
     * @ORM\JoinColumn(name="paymentmethod", referencedColumnName="id")
     * @Groups({"public_api"})
     */
    protected $paymentmethod;
    
    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    /**
     * @ORM\ManyToOne(targetEntity="SaleStatus")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     * @Groups({"public_api"})
     */
     protected $status;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Sale
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Sale
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
     * Set totalWithTax
     *
     * @param float $totalWithTax
     * @return Sale
     */
    public function setTotalWithTax($totalWithTax)
    {
        $this->totalWithTax = $totalWithTax;

        return $this;
    }

    /**
     * Get totalWithTax
     *
     * @return float 
     */
    public function getTotalWithTax()
    {
        return $this->totalWithTax;
    }

    /**
     * Set tax
     *
     * @param float $tax
     * @return Sale
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return float 
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set observations
     *
     * @param string $observations
     * @return Sale
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string 
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->saleItems = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add saleItems
     *
     * @param \Flower\ModelBundle\Entity\Sales\SaleItem $saleItems
     * @return Sale
     */
    public function addSaleItem(\Flower\ModelBundle\Entity\Sales\SaleItem $saleItems)
    {
        $this->saleItems[] = $saleItems;

        return $this;
    }

    /**
     * Remove saleItems
     *
     * @param \Flower\ModelBundle\Entity\Sales\SaleItem $saleItems
     */
    public function removeSaleItem(\Flower\ModelBundle\Entity\Sales\SaleItem $saleItems)
    {
        $this->saleItems->removeElement($saleItems);
    }

    /**
     * Get saleItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSaleItems()
    {
        return $this->saleItems;
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Sale
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Sale
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set paymentmethod
     *
     * @param \Flower\ModelBundle\Entity\Sales\PaymentMethod $paymentmethod
     * @return Sale
     */
    public function setPaymentmethod(\Flower\ModelBundle\Entity\Sales\PaymentMethod $paymentmethod = null)
    {
        $this->paymentmethod = $paymentmethod;

        return $this;
    }

    /**
     * Get paymentmethod
     *
     * @return \Flower\ModelBundle\Entity\Sales\PaymentMethod 
     */
    public function getPaymentmethod()
    {
        return $this->paymentmethod;
    }


    /**
     * Set contact
     *
     * @param \Flower\ModelBundle\Entity\Clients\Contact $contact
     * @return Sale
     */
    public function setContact(\Flower\ModelBundle\Entity\Clients\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \Flower\ModelBundle\Entity\Clients\Contact 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set account
     *
     * @param \Flower\ModelBundle\Entity\Clients\Account $account
     * @return Sale
     */
    public function setAccount(\Flower\ModelBundle\Entity\Clients\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Flower\ModelBundle\Entity\Clients\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
    
    /**
     * Set owner
     *
     * @param \Flower\ModelBundle\Entity\User\User $owner
     * @return Sale
     */
    public function setOwner(\Flower\ModelBundle\Entity\User\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Flower\ModelBundle\Entity\User\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set status
     *
     * @param \Flower\ModelBundle\Entity\Sales\SaleStatus $status
     * @return Sale
     */
    public function setStatus(\Flower\ModelBundle\Entity\Sales\SaleStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Flower\ModelBundle\Entity\Sales\SaleStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
