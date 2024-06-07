<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('categoria')
            ->add('contenido')
            ->add('imagen', FileType::class, [
                'label' => 'Imagen del post',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG, WEBP).',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }

    public function preSubmit(array $options) {
        $post = $options['data'];
        $user = $post->getAutor();
        
        if($user === null) {

            $user = $this->getUser();
            $post->setAutor($user);
        }
    }
}
