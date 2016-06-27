<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * See `Resources/doc/knob/overview.md` for documentation
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class KnobType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $class = 'knob';
        if (array_key_exists('attr', $options) && array_key_exists('class', $options['attr'])) {
            $class = $options['attr']['class'].' '.$class;
        }

        $style = $options['hide_box_shadow'] ? 'box-shadow:none;' : '';
        if (array_key_exists('attr', $options) && array_key_exists('style', $options['attr'])) {
            $style = trim($options['attr']['style'].' '.$style);
        }

        $view->vars = array_merge(
            $view->vars,
            array(
                'width'           => $options['width'],
                'height'          => $options['height'],
                'displayInput'    => $options['displayInput'],
                'displayPrevious' => $options['displayPrevious'],
                'angleArc'        => $options['angleArc'],
                'angleOffset'     => $options['angleOffset'],
                'cursor'          => $options['cursor'],
                'readOnly'        => $options['readOnly'],
                'thickness'       => $options['thickness'],
                'fgColor'         => $options['fgColor'],
                'bgColor'         => $options['bgColor'],
                'lineCap'         => $options['lineCap'],
                'step'            => $options['step'],
                'min'             => $options['min'],
                'max'             => $options['max'],
                'attr'            => array(
                    'class' => $class,
                    'style' => $style,
                )
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'width'           => 200,
            'height'          => 200,
            'displayInput'    => true,
            'displayPrevious' => false,
            'angleArc'        => 0,
            'angleOffset'     => 0,
            'cursor'          => true,
            'readOnly'        => false,
            'thickness'       => 0.35,
            'fgColor'         => '#87CEEB',
            'bgColor'         => '#EEEEEE',
            'lineCap'         => 'butt',
            'step'            => 1,
            'min'             => 0,
            'max'             => 100,
            'hide_box_shadow' => true,
        ));

        $resolver->setAllowedTypes('width', array('integer'));
        $resolver->setAllowedTypes('height', array('integer'));
        $resolver->setAllowedTypes('displayInput', array('bool'));
        $resolver->setAllowedTypes('displayPrevious', array('bool'));
        $resolver->setAllowedTypes('angleArc', array('numeric'));
        $resolver->setAllowedTypes('angleOffset', array('numeric'));
        $resolver->setAllowedTypes('cursor', array('numeric', 'bool'));
        $resolver->setAllowedTypes('readOnly', array('bool'));
        $resolver->setAllowedTypes('thickness', array('numeric'));
        $resolver->setAllowedTypes('fgColor', array('string'));
        $resolver->setAllowedTypes('bgColor', array('string'));
        $resolver->setAllowedTypes('step', array('numeric'));
        $resolver->setAllowedTypes('min', array('numeric'));
        $resolver->setAllowedTypes('max', array('numeric'));
        $resolver->setAllowedTypes('hide_box_shadow', array('bool'));

        $resolver->setAllowedValues('angleArc', range(0, 359));
        $resolver->setAllowedValues('angleOffset', range(0, 359));
        $resolver->setAllowedValues('lineCap', array('butt', 'round'));
    }

    public function getParent()
    {
        return 'number';
    }

    public function getName()
    {
        return 'afe_knob';
    }
}
