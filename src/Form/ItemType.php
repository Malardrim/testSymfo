<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Phase;
use App\Entity\Rule;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('points')
            ->add('description', CKEditorType::class)
            ->add('type')
            ->add('reach')
            ->add('phases', EntityType::class, [
                'class' => Phase::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => 'true'
                ]
            ])
            ->add('faction')
            ->add('strength')
            ->add('damage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
