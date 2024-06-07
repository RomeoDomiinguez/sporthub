<?php

// src/Controller/Admin/ProductoCrudController.php

namespace App\Controller\Admin;

use App\Entity\Producto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProductoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Producto::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nombre'),
            TextareaField::new('descripcion'),
            MoneyField::new('precio')->setCurrency('EUR'),
            ImageField::new('imagen')
                ->setBasePath('/images/') // Ruta base para mostrar las imágenes
                ->setUploadDir('public/images/') // Directorio donde se guardarán las imágenes
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Patrón para generar nombres de archivo
                ->setRequired(false), // No obligatorio
        ];
    }
}
