<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Avocode\FormExtensionsBundle\Form\DataTransformer\ArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See `Resources/doc/select2/overview.md` for documentation
 *
 * @author Bilal Amarni <bilal.amarni@gmail.com>
 * @author Chris Tickner <chris.tickner@gmail.com>
 */
class Select2Type extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('hidden' === $this->widget && !empty($options['configs']['multiple'])) {
            $builder->addViewTransformer(new ArrayToStringTransformer());
        } elseif ('hidden' === $this->widget && empty($options['configs']['multiple']) && null !== $options['transformer']) {
            $builder->addModelTransformer($options['transformer']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];
        $view->vars['hidden'] = $options['hidden'];

        // Adds a custom block prefix
        array_splice(
            $view->vars['block_prefixes'],
            array_search($this->getName(), $view->vars['block_prefixes']),
            0,
            'afe_select2'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = array(
            'placeholder'        => 'Select a value',
            'allowClear'         => false,
            'minimumInputLength' => 0,
            'width'              => 'element',
        );

        $resolver
            ->setDefaults(array(
                'hidden'        => false,
                'configs'       => $defaults,
                'transformer'   => null,
            ))
            ->setNormalizer('configs', function (Options $options, $configs) use ($defaults) {
                return array_merge($defaults, $configs);
            })
        ;
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
        } elseif ($this->widget == 'model') {
            return 'Symfony\Bridge\Propel1\Form\Type\ModelType';
        }

        return 'Symfony\Component\Form\Extension\Core\Type\\'.ucfirst($this->widget).'Type';
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
        return 'afe_select2_' . $this->widget;
    }
}
