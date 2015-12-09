<?php

namespace Flower\SalesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('date')
            ->add('total')
            ->add('totalWithTax')
            ->add('tax')
            ->add('observations')
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
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sale';
    }
}
