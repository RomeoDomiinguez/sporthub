<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('direccion', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('ciudad', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('codigoPostal', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('numeroTarjeta', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 9, 'max' => 9]),
                    new Regex([
                        'pattern' => '/^\d{9}$/',
                        'message' => 'El número de tarjeta debe contener exactamente 9 dígitos.',
                    ]),
                ],
            ])
            ->add('fechaExpiracion', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
                        'message' => 'La fecha de expiración debe tener el formato MM/YY.',
                    ]),
                ],
            ])
            ->add('cvv', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 4]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Pagar',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}