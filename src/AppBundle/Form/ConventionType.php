<?php

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConventionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',      TextType::class)
            ->add('content',    TextareaType::class)
            ->add('street',     TextType::class)
            ->add('number',     NumberType::class)
            ->add('code',       NumberType::class)
            ->add('locality',   TextType::class)
            ->add('dateStart',  DateTimeType::class)
            ->add('dateEnd',    DateTimeType::class)
            ->add('website',    UrlType::class)
            ->add('facebook',   UrlType::class)
            ->add('mobile',     TelType::class)
            ->add('email',      EmailType::class)
            ->add('image',      ImageType::class)
            ->add('poster',     PosterType::class)
            ->add('flyers',     FlyersType::class)
            ->add('partenaire', EntityType::class, array(
                'class'        => 'AppBundle\Entity\Partenaire',
                'choice_label' => 'name',
                'multiple'     => true,
            ))
            ->add('published',  CheckboxType::class, array('required' => false))
            ->add('save',       SubmitType::class);
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Convention'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_convention';
    }


}
