<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulari per al registre de nous usuaris.
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Defineix els camps del formulari de registre
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom Complet',
                'attr' => ['placeholder' => 'El teu nom real'],
                'constraints' => [
                    new NotBlank(['message' => 'Si us plau, introdueix el teu nom.']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correu Electrònic',
                'attr' => ['placeholder' => 'usuari@exemple.com'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepto els termes i condicions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Has d\'acceptar els nostres termes.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // En lloc d'assignar-se directament a l'objecte,
                // aquest camp es llegeix i s'hasheja al controlador.
                'mapped' => false,
                'label' => 'Contrasenya',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mínim 6 caràcters'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Si us plau, introdueix una contrasenya.',
                    ]),
                    new Length(
                        min: 6,
                        minMessage: 'La teva contrasenya ha de tenir almenys {{ limit }} caràcters.',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
