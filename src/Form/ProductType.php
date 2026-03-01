<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Formulari per a la creació i edició de productes.
 */
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Defineix els camps del formulari amb les seves validacions (Assert)
        $builder
            ->add('title', TextType::class, [
                'label' => 'Títol del Producte',
                'attr' => ['placeholder' => 'E.g. Bicicleta de muntanya'],
                'constraints' => [
                    new NotBlank(message: 'El títol no pot estar buit.'),
                    new Length(
                        min: 3,
                        max: 255,
                        minMessage: 'El títol ha de tenir almenys {{ limit }} caràcters.',
                    ),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripció',
                'attr' => ['rows' => 5, 'placeholder' => 'Explica els detalls del producte...'],
                'constraints' => [
                    new NotBlank(message: 'La descripció és obligatòria.'),
                    new Length(
                        min: 10,
                        minMessage: 'La descripció ha de tenir almenys {{ limit }} caràcters.',
                    ),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Preu',
                'html5' => true,
                'attr' => ['step' => '0.01', 'placeholder' => '0.00'],
                'constraints' => [
                    new NotBlank(message: 'Indica un preu.'),
                    new Positive(message: 'El preu ha de ser positiu.'),
                ],
            ])
            ->add('image', UrlType::class, [
                'label' => 'URL de la Imatge (Opcional)',
                'required' => false,
                'attr' => ['placeholder' => 'https://exemple.com/imatge.jpg'],
                'constraints' => [
                    new Url(['message' => 'La URL de la imatge no és vàlida.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
