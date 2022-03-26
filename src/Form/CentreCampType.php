<?php

namespace App\Form;

use App\Entity\CentreCamp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;


class CentreCampType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_centre')
            ->add('Description_centre')
            ->add('img_centre', FileType::class, array('data_class'=>null, 'required' => false ))
            ->add('lieux')
            ->add('tlf_centre')
            ->add('mail_centre')
            ->add('mdps_centre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CentreCamp::class,
        ]);
    }
}
