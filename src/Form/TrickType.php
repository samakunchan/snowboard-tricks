<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('trick_group', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'title',
            ])
            ->add('images', CollectionType::class, [
                'entry_type'   => ImageType::class,
                'allow_add'    => true,
                'allow_delete' => true
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'required' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
