<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Le RegistrationFormType définit le formulaire d’inscription des utilisateurs.
 * Il permet de collecter les informations nécessaires à la création d’un compte,
 * telles que le type d’utilisateur (étudiant ou professeur), l’email,
 * le nom, le prénom et le mot de passe, tout en appliquant des contraintes
 * de validation.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Construit le formulaire d’inscription.
     * Cette méthode définit les différents champs du formulaire,
     * leurs types, leurs labels, ainsi que les contraintes de validation
     * associées à chaque champ.
     *
     * @param FormBuilderInterface $builder Interface permettant de construire le formulaire
     * @param array $options Options de configuration du formulaire
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('userType', ChoiceType::class, [
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'label' => 'Vous êtes',
                'choices' => [
                    'Étudiant' => 'student',
                    'Professeur' => 'teacher',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un profil.',
                    ]),
                ],
            ])

            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'exemple@edulearn.fr',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse email.',
                    ]),
                ],
            ])

            ->add('name', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Jean',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom.',
                    ]),
                ],
            ])

            ->add('surname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Dupont',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom.',
                    ]),
                ],
            ])

            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => '••••••••',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J’accepte les conditions d’utilisation',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d’utilisation.',
                    ]),
                ],
            ]);
    }

    /**
     * Configure les options par défaut du formulaire.
     * Associe le formulaire à l’entité User afin que les données
     * soient automatiquement mappées sur cet objet.
     *
     * @param OptionsResolver $resolver Gestionnaire des options du formulaire
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
