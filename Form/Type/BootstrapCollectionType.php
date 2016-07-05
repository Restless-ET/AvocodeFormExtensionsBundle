<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See `Resources/doc/bootstrap-collection/overview.md` for documentation
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class BootstrapCollectionType extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sortable']       = $options['sortable'];
        $view->vars['sortable_field'] = $options['sortable_field'];
        $view->vars['new_label']      = $options['new_label'];
        $view->vars['prototype_name'] = $options['prototype_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'sortable'        => false,
            'sortable_field'  => 'position',
            'new_label'       => 'afe_collection.new_label'
        ));

        $resolver->setAllowedTypes('sortable', array('bool'));
        $resolver->setAllowedTypes('sortable_field', array('string'));
        $resolver->setAllowedTypes('new_label', array('string'));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'collection';
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
        return 'afe_collection_' . $this->widget;
    }
}
