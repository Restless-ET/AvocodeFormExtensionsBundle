<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See `Resources/doc/time-picker/overview.md` for documentation
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class TimePickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge(
            $view->vars,
            array(
                'minute_step'   => $options['minute_step'],
                'second_step'   => $options['second_step'],
                'with_seconds'  => $options['with_seconds'],
                'default_time'  => $options['default_time'],
                'show_meridian' => $options['show_meridian'],
                'disable_focus' => $options['disable_focus'],
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget'        => 'single_text',
            'minute_step'   => 15,
            'second_step'   => 15,
            'default_time'  => 'current',
            'show_meridian' => false,
            'disable_focus' => false,
            'attr'          => array(
                'class' => 'input-small',
            ),
        ));

        $resolver->setAllowedTypes('minute_step', array('integer'));
        $resolver->setAllowedTypes('second_step', array('integer'));
        $resolver->setAllowedTypes('default_time', array('string', 'bool'));
        $resolver->setAllowedTypes('show_meridian', array('bool'));
        $resolver->setAllowedTypes('disable_focus', array('bool'));
    }

    public function getParent()
    {
        // BC for Symfony < 3
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return 'time';
        }

        return 'Symfony\Component\Form\Extension\Core\Type\TimeType';
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
        return 'afe_time_picker';
    }
}
