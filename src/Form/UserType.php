<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Pseudo'
                ]
            ]
        );

        $builder->add(
            'firstName',
            TextType::class,
            [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Firstname'
                ]
            ]
        );

        $builder->add(
            'lastName',
            TextType::class,
            [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Lastname'
                ]
            ]
        );

        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Email'
                ]
            ]
        );

        $builder->add(
            'phoneNumber',
            TextType::class,
            [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'N° de téléphone'
                ]
            ]
        );

        $builder->add(
            'streetAddress',
            TextType::class,
            [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Street Name'
                ]
            ]
        );

        $builder->add(
            'numberStreetAddress',
            TextType::class,
            [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Street Number'
                ]
            ]
        );

        $builder->add(
            'postalCode',
            TextType::class,
            [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Postal code'
                ]
            ]
        );

        $builder->add(
            'town',
            TextType::class,
            [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Town'
                ]
            ]
        );

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe'
                ]
            ],
            'second_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Confirmation du MDP'
                ]
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
