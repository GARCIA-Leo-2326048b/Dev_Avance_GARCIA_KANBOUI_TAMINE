<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * Construit le formulaire d'inscription.
     *
     * Définit les champs du formulaire :
     * - email
     * - acceptation des conditions d'utilisation
     * - mot de passe (non mappé, encodé dans le contrôleur)
     *
     * @param FormBuilderInterface $builder le constructeur de formulaire
     * @param array $options les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('name', TextType::class, [
                'label' => 'name',
            ])
            ->add('surname', TextType::class, [
                'label' => 'surname',
            ])
            ->add('userType', ChoiceType::class, [
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'label' => 'Vous êtes :',
                'choices' => [
                    'Étudiant' => 'student',
                    'Professeur' => 'teacher',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un profil (étudiant ou professeur).',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    /**
     * Configure les options du formulaire.
     *
     * Associe le formulaire à l'entité User.
     *
     * @param OptionsResolver $resolver le gestionnaire d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
