<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;


class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(groups: ['new', 'edit']),
                    new Assert\Length(['max' => 255, 'groups' => ['new', 'edit']]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                ],
                'constraints' => [
                   // new Assert\NotBlank(groups: ['new']),
                ],
            ])
            ->add('latitude', TextType::class, [
                'constraints' => [
                //    new Assert\NotBlank(groups: ['new']),
//                    new Assert\Regex([
//                        'message' => 'Enter a valid latitude.',
//                        'groups' => ['new', 'edit'],
//                    ]),
                ],
            ])
            ->add('longitude', TextType::class, [
                'constraints' => [
                //    new Assert\NotBlank(groups: ['new']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'validation_groups' => function (FormBuilderInterface $form) {

                return $form->getData() && $form->getData()->getId() ? ['edit'] : ['new'];
            },
        ]);
    }
}
