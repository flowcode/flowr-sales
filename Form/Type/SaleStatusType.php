<?php

namespace Flower\SalesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleStatusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('name')
            ->add('saleModificable')
            ->add('saleDeleted')
            ->add('invoiceable')
            ->add('stockModifier')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\ModelBundle\Entity\Sales\SaleStatus',
            'translation_domain' => 'SaleStatus',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'salestatus';
    }
}
