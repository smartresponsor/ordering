<?php

declare(strict_types=1);

namespace App\Ordering\Form;

use App\Ordering\DTO\OrderCreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('currency', TextType::class, [
                'label' => 'Currency',
                'help' => 'Three-letter ISO currency code, e.g. USD, EUR, UAH.',
            ])
            ->add('grandTotal', MoneyType::class, [
                'label' => 'Grand total',
                'currency' => false,
                'divisor' => 1,
                'scale' => 2,
                'help' => 'Order total before any post-payment adjustments.',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Create order']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => OrderCreateDTO::class,
        ]);
    }
}
