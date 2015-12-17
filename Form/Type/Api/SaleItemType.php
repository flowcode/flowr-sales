<?php

namespace Flower\SalesBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('units')
            ->add('unitPrice')
            ->add("product", 'entity', array(
                                'class' => 'Flower\ModelBundle\Entity\Stock\Product'))
            ->add('total');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flower\ModelBundle\Entity\Sales\SaleItem',
            'translation_domain' => 'SaleItem',
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
