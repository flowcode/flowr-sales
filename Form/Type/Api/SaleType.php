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
            ->add('totalWithTax')
            ->add('tax')
            ->add('account', 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Clients\Account'))
            ->add('contact', 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Clients\Contact'))
            ->add('observations')
            ->add("paymentMethod", 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Sales\PaymentMethod'))
            ->add('saleItems', 'collection', array(
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
