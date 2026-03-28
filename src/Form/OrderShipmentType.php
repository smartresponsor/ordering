<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\OrderShipmentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderShipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carrier', TextType::class, ['label' => 'Carrier'])
            ->add('note', TextareaType::class, [
                'label' => 'Operational note',
                'required' => false,
                'attr' => ['rows' => 2],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Ship order']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderShipmentDTO::class,
        ]);
    }
}
