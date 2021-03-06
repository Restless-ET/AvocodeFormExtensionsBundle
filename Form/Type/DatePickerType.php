<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * See `Resources/doc/date-picker/overview.md` for documentation
 *
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DatePickerType extends AbstractType
{
    private $locale;

    private static $acceptedFormats = array(
        \IntlDateFormatter::FULL,
        \IntlDateFormatter::LONG,
        \IntlDateFormatter::MEDIUM,
        \IntlDateFormatter::SHORT,
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateFormat = \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = $options['format'];

        if (!in_array($dateFormat, self::$acceptedFormats, true)) {
            throw new InvalidOptionsException('The "format" option must be one of the IntlDateFormatter constants (FULL, LONG, MEDIUM, SHORT) or a string representing a custom format.');
        }

        $builder->addViewTransformer(new DateTimeToLocalizedStringTransformer(
            null,
            null,
            $dateFormat,
            $timeFormat,
            $calendar,
            $pattern
        ));

        if ($options['input'] == 'single_text') {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToStringTransformer(null, null, 'Y-m-d')
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge(
            $view->vars,
            array(
                'formatSubmit'    => $options['formatSubmit'],
                'weekStart'       => $options['weekStart'],
                'calendarWeeks'   => $options['calendarWeeks'],
                'startDate'       => $options['startDate'],
                'endDate'         => $options['endDate'],
                'disabled'        => $options['disabled'],
                'autoclose'       => $options['autoclose'],
                'startView'       => $options['startView'],
                'minViewMode'     => $options['minViewMode'],
                'todayButton'     => is_bool($options['todayButton'])
                                     ? json_encode($options['todayButton'])
                                     : $options['todayButton'],
                'todayHighlight'  => $options['todayHighlight'],
                'clearButton'     => $options['clearButton'],
                'language'        => !$options['language']
                                     ? $this->getLocale()
                                     : $options['language'],
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'input'           => 'datetime',
            'format'          => 'yyyy-MM-dd',
            'formatSubmit'    => 'yyyy-mm-dd',
            'weekStart'       => 1,
            'calendarWeeks'   => false,
            'startDate'       => date('Y-m-d', strtotime('-20 years')),
            'endDate'         => date('Y-m-d', strtotime('+20 years')),
            'disabled'        => array(),
            'autoclose'       => true,
            'startView'       => 'month',
            'minViewMode'     => 'days',
            'todayButton'     => false,
            'todayHighlight'  => false,
            'clearButton'     => false,
            'language'        => false,
            'attr'            => array(
                'class' => 'input-small'
            ),
        ));

        $resolver->setAllowedTypes('format', array('string'));
        $resolver->setAllowedTypes('formatSubmit', array('string'));
        $resolver->setAllowedTypes('calendarWeeks', array('bool'));
        $resolver->setAllowedTypes('disabled', array('array'));
        $resolver->setAllowedTypes('autoclose', array('bool'));
        $resolver->setAllowedTypes('todayHighlight', array('bool'));
        $resolver->setAllowedTypes('clearButton', array('bool'));

        $resolver->setAllowedValues('weekStart', range(0, 6));
        $resolver->setAllowedValues('startView', array(0, 'month', 1, 'year', 2, 'decade'));
        $resolver->setAllowedValues('minViewMode', array(0, 'days', 1, 'months', 2, 'years'));
        $resolver->setAllowedValues('todayButton', array(true, false, 'linked'));
    }

    public function getParent()
    {
        // BC for Symfony < 3
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return 'text';
        }

        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
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
        return 'afe_date_picker';
    }

    /**
     * Gets Locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets Locale
     *
     * @param string $locale Locale
     * @return string
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
