<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('place')
            ->add('scheduleStart', TimeType::class)
            ->add('scheduleEnd', TimeType::class)
            ->add('salary', NumberType::class, [
                'html5' => true,
                'constraints' => [
                    new PositiveOrZero()
                ]
            ])
            ->add('yearExperienceRequired', NumberType::class, [
                'html5' => true,
                'constraints' => [
                    new PositiveOrZero()
                ]
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
