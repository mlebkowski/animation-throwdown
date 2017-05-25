<?php

namespace Nassau\CartoonBattle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

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
        $builder->add('standings', CollectionType::class, [
            'label' => false,
            'entry_type' => RumbleStandingAdminType::class,
            'entry_options' => [
                'relation_to' => RumbleStandingAdminType::RELATION_TO_RUMBLE
            ],
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
