<?php

namespace Nassau\CartoonBattle\Form;

use Kunstmaan\AdminBundle\Form\WysiwygType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * The type for Guild
 */
class GuildAdminType extends AbstractType
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
        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'constraints' => new NotBlank(),
        ]);

        $builder->add('factionId', TextType::class, [
            'label' => 'In-game guild id',
            'required' => false,
        ]);

        $builder->add('recruiting', CheckboxType::class, [
            'label' => 'Currently recruiting',
            'required' => false,
        ]);

        $builder->add('message', WysiwygType::class, [
            'label' => 'Custom guild message',
        ]);

        $builder->add('url', UrlType::class, [
            'label' => 'Guild’s website, discord, recruitment thread or other URL',
            'required' => false,
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'guild_form';
    }
}
