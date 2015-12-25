<?php

namespace Flower\SalesBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SaleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('total')
            ->add('total_with_tax',null,array(
                'property_path' => 'totalWithTax'))
            ->add('tax')
            ->add('circuit')
            ->add('discount')
            ->add('discount_type',null,array(
                'property_path' => 'discountType'))
            ->add('total_discount',null,array(
                'property_path' => 'totalDiscount'))
            ->add('account', 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Clients\Account'))
            ->add('contact', 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Clients\Contact'))
            ->add('payment_observations',null,array(
                'property_path' => 'paymentObservations'))
            ->add('observations')
            ->add("status", 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Sales\SaleStatus'))
            ->add("paymentmethod", 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Sales\PaymentMethod'))
            ->add('sale_items', 'collection', array(
                                'by_reference' => false,
                                'type' => new SaleItemType(),
                                'allow_add'    => true))
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\ModelBundle\Entity\Sales\Sale',
            'translation_domain' => 'Sale',
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '';
    }
}
