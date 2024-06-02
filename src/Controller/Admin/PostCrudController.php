<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('titulo'),
            TextField::new('categoria'),
            TextareaField::new('contenido'),
            ImageField::new('imagen')
                ->setBasePath('uploads/') // Ruta base para las imágenes (ajustar según la configuración de tu aplicación)
                ->setUploadDir('public/uploads') // Directorio donde se guardan las imágenes
                ->setRequired(false)
                ->hideOnIndex()
                ->setLabel('Imagen del post')
                ->setFormTypeOptions([
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/webp'
                            ],
                            'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG, WEBP).',
                        ]),
                    ],
                ]),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Post')
            ->setEntityLabelInPlural('Posts')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('titulo'));
    }
}
