<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See `Resources/doc/mini-colors/overview.md` for documentation
 *
 * @author Escandell Stéphane <stephane.escandell@gmail.com>
 */
class MiniColorsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge(
            $view->vars,
            array(
                'configs' => array(
                    'animationSpeed'  => $options['animationSpeed'],
                    'animationEasing' => $options['animationEasing'],
                    'changeDelay'     => $options['changeDelay'],
                    'control'         => $options['control'],
                    'hideSpeed'       => $options['hideSpeed'],
                    'inline'          => $options['inline'],
                    'letterCase'      => $options['letterCase'],
                    'opacity'         => $options['opacity'],
                    'position'        => $options['position'],
                    'showSpeed'       => $options['showSpeed'],
                    'swatchPosition'  => $options['swatchPosition'],
                    'textfield'       => $options['textfield'],
                    'theme'           => $options['theme'],
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'animationSpeed'  => 100,
            'animationEasing' => 'swing',
            'changeDelay'     => 0,
            'control'         => 'hue',
            'hideSpeed'       => 100,
            'inline'          => false,
            'letterCase'      => 'lowercase',
            'opacity'         => false,
            'position'        => 'default',
            'showSpeed'       => 100,
            'swatchPosition'  => 'left',
            'textfield'       => true,
            'theme'           => 'bootstrap'
        ));

        $resolver->setAllowedValues('control', array('hue', 'brightness', 'saturation', 'wheel'));
        $resolver->setAllowedValues('letterCase', array('lowercase', 'uppercase'));
        $resolver->setAllowedValues('position', array('default', 'top', 'left', 'top left'));
        $resolver->setAllowedValues('swatchPosition', array('left', 'right'));

        $resolver->setAllowedTypes('animationSpeed', array('integer'));
        $resolver->setAllowedTypes('animationEasing', array('string'));
        $resolver->setAllowedTypes('changeDelay', array('integer'));
        $resolver->setAllowedTypes('hideSpeed', array('integer'));
        $resolver->setAllowedTypes('inline', array('bool'));
        $resolver->setAllowedTypes('opacity', array('bool'));
        $resolver->setAllowedTypes('showSpeed', array('integer'));
        $resolver->setAllowedTypes('textfield', array('bool'));
        $resolver->setAllowedTypes('theme', array('string'));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
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
        return 'afe_mini_colors';
    }
}
