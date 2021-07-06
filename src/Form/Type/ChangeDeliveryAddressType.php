<?php

namespace App\Form\Type;

use App\Entity\DeliveryAddress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeDeliveryAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'deliveryAddress',
            EntityType::class,
            [
                'class' => DeliveryAddress::class,
                'choice_label' => 'addressTitle',
                'mapped' => false,
                'attr'  => [
                    'placeholder' => 'Address Title',
                    'class' => 'input-field'
                ]
            ]
        );

        $builder->add(
            'Submit', SubmitType::class, [
            'attr' => [
                'placeholder' =>'Submit',
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
