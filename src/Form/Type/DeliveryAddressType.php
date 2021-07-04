<?php

namespace App\Form\Type;

use App\Entity\DeliveryAddress;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'addressTitle',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Address Title',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'firstName',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Firstname',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'lastName',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Lastname',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'numberStreet',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'N° street',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'streetName',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Street name',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'postalCode',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Postal Code',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'town',
            TextType::class,
            [
                'required' => false,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Town',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'send', SubmitType::class, [
            'attr' => [
                'placeholder' =>'Send',
                    'class' => 'btn btn-medium waves-effect'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeliveryAddress::class,
        ]);
    }
}
