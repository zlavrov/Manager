<?php

namespace App\Form;

use App\Model\In\Task\TaskCreateIn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CreateTaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['placeholder' => 'Title', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Title',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your Title should be at least {{ limit }} characters',
                        'max' => 4096,
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'attr' => ['class' => 'form-control', 'placeholder' => 'Description'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Description',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your Description should be at least {{ limit }} characters',
                        'max' => 4096,
                    ])
                ],
            ])
            ->add('deadline', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',

            ])
            ->add('status', ChoiceType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Description'],
                'choices'  => [
                    'New' => 'new',
                    'In Progress' => 'in progress',
                    'Complete' => 'complete'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskCreateIn::class,
        ]);
    }
}
