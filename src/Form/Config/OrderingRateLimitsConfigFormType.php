<?php

declare(strict_types=1);

namespace App\Ordering\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderingRateLimitsConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apiWriteLimit', NumberType::class, ['scale' => 0])
            ->add('apiWriteIntervalMinutes', NumberType::class, ['scale' => 0])
            ->add('apiReadLimit', NumberType::class, ['scale' => 0])
            ->add('apiReadIntervalMinutes', NumberType::class, ['scale' => 0]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderingRateLimitsConfigData::class,
            'csrf_protection' => true,
            'allow_extra_fields' => false,
        ]);
    }
}
