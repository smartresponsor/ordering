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
        $builder
            ->add('amount', MoneyType::class, [
                'currency' => false,
                'divisor' => 1,
                'scale' => 2,
            ])
            ->add('externalRef', TextType::class, ['label' => 'External reference'])
            ->add('submit', SubmitType::class, ['label' => 'Capture payment']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderPaymentDTO::class,
        ]);
    }
}
