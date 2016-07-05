<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See `Resources/doc/double-list/overview.md` for documentation
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DoubleListType extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'multiple'    => true,
            'attr'        => array(
                'class' => 'hidden-select',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        // BC for Symfony < 3
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return $this->widget;
        }

        if ($this->widget == 'entity') {
            return 'Symfony\Bridge\Doctrine\Form\Type\EntityType';
        } elseif ($this->widget == 'document') {
            return 'Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType';
        }

        return 'Symfony\Bridge\Propel1\Form\Type\ModelType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->getName();
    }

    /**
     * BC for Symfony < 3.0.
     */
    public function getName()
    {
        return 'afe_double_list_' . $this->widget;
    }
}
