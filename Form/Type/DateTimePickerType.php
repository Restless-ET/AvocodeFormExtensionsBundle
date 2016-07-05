<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Avocode\FormExtensionsBundle\Form\DataTransformer\DateTimeToPartsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * See `Resources/doc/datetime-picker/overview.md` for documentation
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DateTimePickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateOptions = array_intersect_key(
            $options,
            array_flip(array(
                'format',
                'formatSubmit',
                'weekStart',
                'calendarWeeks',
                'startDate',
                'endDate',
                'disabled',
                'autoclose',
                'startView',
                'minViewMode',
                'todayButton',
                'todayHighlight',
                'clearButton',
                'language',
            ))
        );

        $timeOptions = array_intersect_key(
            $options,
            array_flip(array(
                'minute_step',
                'with_seconds',
                'second_step',
                'default_time',
                'show_meridian',
                'disable_focus',
            ))
        );

        $builder
            ->resetViewTransformers()
            ->remove('date')
            ->remove('time')
            ->addViewTransformer(new DateTimeToPartsTransformer())
            ->add('date', 'afe_date_picker', $dateOptions)
            ->add('time', 'afe_time_picker', $timeOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace(
            $view->vars,
            array(
                'weekStart'     => $options['weekStart'],
                'startView'     => $options['startView'],
                'minViewMode'   => $options['minViewMode'],
                'minute_step'   => $options['minute_step'],
                'second_step'   => $options['second_step'],
                'disable_focus' => $options['disable_focus'],
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'format'          => 'yyyy-MM-dd',
            'formatSubmit'    => 'yyyy-mm-dd',
            'calendarWeeks'   => false,
            'weekStart'       => 1,
            'startView'       => 'month',
            'minViewMode'     => 'days',
            'minute_step'     => 15,
            'second_step'     => 15,
            'disable_focus'   => false,
            'default_time'    => 'current',
            'disabled'        => array(),
            'todayButton'     => false,
            'todayHighlight'  => false,
            'clearButton'     => false,
            'language'        => false,
            'attr'            => array(
                'class' => 'input-small'
            ),
        ));

        $resolver->setAllowedValues('weekStart', range(0, 6));
        $resolver->setAllowedValues('startView', array(0, 'month', 1, 'year', 2, 'decade'));
        $resolver->setAllowedValues('minViewMode', array(0, 'days', 1, 'months', 2, 'years'));

        $resolver->setAllowedTypes('format', array('string'));
        $resolver->setAllowedTypes('formatSubmit', array('string'));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        // BC for Symfony < 3
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return 'datetime';
        }

        return 'Symfony\Component\Form\Extension\Core\Type\DateTimeType';
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
        return 'afe_datetime_picker';
    }
}
