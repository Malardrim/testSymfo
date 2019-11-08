<?php

namespace App\Form;

use App\Entity\Faction;
use App\Entity\Phase;
use App\Entity\Rule;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['type'] && $options['type'] == 'hidden')
            $builder->add('type', HiddenType::class);
        else
            $builder->add('type');
        $builder
            ->add('name')
            ->add('description', CKEditorType::class)
            ->add('origin')
            ->add('lastUpdateOrigin')
            ->add('faction', EntityType::class, [
                'class' => Faction::class,
                'placeholder' => 'rule.faction_none',
                'required' => false
            ])
            ->add('phases', EntityType::class, [
                'class' => Phase::class,
                'placeholder' => 'Any phase',
                'multiple' => true,
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => 'normal',
            'data_class' => Rule::class,
            'translation_domain' => 'forms',
            'label_format' => 'rule.%name%',
        ]);
    }
}
