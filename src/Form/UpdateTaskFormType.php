<?php

namespace App\Form;

use App\Model\In\Task\TaskUpdateIn;

use App\Model\Out\Task\TaskListOut;

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

class UpdateTaskFormType extends AbstractType
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
                ],
                    // 'data' => 'Ваше значение'
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
            'data_class' => TaskListOut::class,
        ]);
    }
}

























// "action", 
// "allow_extra_fields", 
// "allow_file_upload", 
// "attr", 
// "attr_translation_parameters", 
// "auto_initialize", 
// "block_name", 
// "block_prefix", 
// "by_reference", 
// "compound", 
// "constraints", 
// "csrf_field_name", 
// "csrf_message", 
// "csrf_protection", 
// "csrf_token_id", 
// "csrf_token_manager", 
// "data", "data_class", 
// "disabled", 
// "empty_data", 
// "error_bubbling", 
// "error_mapping", 
// "extra_fields_message", 
// "form_attr", 
// "getter", 
// "help", 
// "help_attr", 
// "help_html", 
// "help_translation_parameters", 
// "inherit_data", 
// "invalid_message", 
// "invalid_message_parameters", 
// "is_empty_callback", 
// "label", 
// "label_attr", 
// "label_format", 
// "label_html", 
// "label_translation_parameters", 
// "mapped", 
// "method", 
// "post_max_size_message", 
// "priority", 
// "property_path", 
// "required", 
// "row_attr", 
// "setter", 
// "translation_domain", 
// "trim", 
// "upload_max_size_message", 
//"validation_groups".