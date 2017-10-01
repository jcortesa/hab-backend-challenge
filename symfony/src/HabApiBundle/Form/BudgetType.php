<?php

namespace HabApiBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'category',
                EntityType::class,
                ['class' => 'HabApiBundle:Category']
            )
            ->add('title', TextType::class, ['data' => null])
            ->add('description')
            ->add('email', TextType::class, ['mapped' => false])
            ->add('phone', TextType::class, ['mapped' => false])
            ->add('address', TextType::class, ['mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HabApiBundle\Entity\Budget',
            'csrf_protection' => false,
        ]);
    }
}
