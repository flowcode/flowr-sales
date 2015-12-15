<?php

namespace Flower\SalesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
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
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    protected $total;

    /**
     * @var float
     *
     * @ORM\Column(name="totalWithTax", type="float")
     */
    protected $totalWithTax;

    /**
     * @var float
     *
     * @ORM\Column(name="tax", type="float")
     */
    protected $tax;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text")
     */
    protected $observations;


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
}
