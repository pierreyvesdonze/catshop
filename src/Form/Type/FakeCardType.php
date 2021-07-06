<?php

namespace App\Form\Type;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FakeCardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fakeCardNumber', TextType::class, [
                'required' => true,
                'label' => 'Fake Card Number',
                'attr' => [
                    'class' => 'fakeCardNumber',
                    'placeholder' => 'xxxx xxxx xxxx xxxx ',
                    'html5' => true,
                ]
            ])
            ->add('fakeDateExpiration', DateType::class, [
                'required' => true,
                'label' => "Fake expiration date"
            ])
            ->add('fakeSecurityCode', NumberType::class, [
                'required' => true,
                'label' => 'Fake security code',
                'attr' => [
                    'class' => 'input-field',
                    'html5' => true,
                    'placeholder' => 'xxx'
                ]
            ])
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Enregistrer',
                    'attr' => [
                        'class' => 'btn btn-medium waves-effect',
                    ],
                ]
            );
    }
}
