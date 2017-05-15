<?php

namespace Nassau\CartoonBattle\Form;

use Nassau\CartoonBattle\Entity\Rumble\RumbleStanding;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * The type for Rumble
 */
class RumbleAdminType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', DateTimeType::class, [
            'label' => 'Start date',
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'constraints' => new NotBlank(),
        ]);
        $builder->add('end', DateTimeType::class, [
            'label' => 'End date',
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'constraints' => new NotBlank(),
        ]);

        $builder->add('standings', CollectionType::class, [
            'label' => false,
            'entry_type' => RumbleStandingAdminType::class,
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'attr' => [
                'nested_form' => true,
            ]
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'rumble_form';
    }
}
