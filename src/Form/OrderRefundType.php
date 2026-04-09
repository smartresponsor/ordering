<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\OrderRefundDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderRefundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('amount', MoneyType::class, [
                'currency' => false,
                'divisor' => 1,
                'scale' => 2,
            ])
            ->add('reason', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 2],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Refund order']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => OrderRefundDTO::class,
        ]);
    }
}
