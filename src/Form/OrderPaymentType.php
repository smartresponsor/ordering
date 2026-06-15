<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\OrderPaymentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'Capture amount',
                'currency' => false,
                'divisor' => 1,
                'scale' => 2,
                'help' => 'The amount to capture against the order.',
            ])
            ->add('externalRef', TextType::class, [
                'label' => 'External reference',
                'help' => 'Optional reference from a payment gateway or PSP.',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Capture payment']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => OrderPaymentDTO::class,
        ]);
    }
}
