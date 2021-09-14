<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecruiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
