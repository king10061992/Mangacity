<?php

namespace AppBundle\Form;


use AppBundle\Entity\Image;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Time;


class PartenaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = 'D%';

        $builder
            ->add('name',           TextType::class)
            ->add('street',         TextType::class)
            ->add('number',         NumberType::class)
            ->add('code',           NumberType::class)
            ->add('locality',       TextType::class)
            ->add('timeStart',      TimeType::class)
            ->add('timeEnd',        TimeType::class)
            ->add('webSite',        UrlType::class)
            ->add('facebook',       UrlType::class)
            ->add('mobile',         TelType::class)
            ->add('email',          EmailType::class)
            ->add('exception',      TextType::class)
            ->add('content',        TextareaType::class)
            ->add('logo',           LogoType::class)
            ->add('image',          ImageType::class)
            ->add('flyers',         FlyersType::class)
            ->add('product', EntityType::class, array(
                'class'        => 'AppBundle\Entity\Product',
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
            'data_class' => 'AppBundle\Entity\Partenaire'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_partenaire';
    }


}
