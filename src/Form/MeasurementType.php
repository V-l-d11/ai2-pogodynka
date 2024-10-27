<?php

namespace App\Form;

use App\Entity\Measurement;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city',
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                ],
                'placeholder' => 'Select a location',
            ])
            ->add('date',  DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',

             'html5' => false,
             //   'format' => 'Y-m-d',
               'constraints' => [
              //      new Assert\NotBlank(groups: ['new', 'edit']),
                 //   new Assert\DateTime(groups: ['new', 'edit']),
                ],
            ])
            ->add('celsius', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Regex([
                        'pattern' => '/^-?\d+(\.\d+)?$/', // Позволяет целые и дробные числа
                        'message' => 'Temperature must be a valid number (e.g., 25 or -5.3).',
                        'groups' => ['new', 'edit'],
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
            'validation_groups' => function ($form) {
                return $form->getData() && $form->getData()->getId() ? ['edit'] : ['new'];
            },
        ]);
    }
}
