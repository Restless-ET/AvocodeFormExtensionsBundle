<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author   Vincent Touzet <vincent.touzet@gmail.com>
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

        if ( $options['input'] == 'single_text' ) {
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
        $today_btn = $options['today_btn'];
        
        if (is_bool($today_btn)) {
            $today_btn = json_encode($today_btn);
        }
        
        $language = $options['language'];
        
        if ($language === false) {
            $language = $this->getLocale();
        }
        
        $view->vars = array_replace($view->vars, array(
            'format' => $options['format']
        ));
        
        if (!array_key_exists('attr', $view->vars) || !is_array($view->vars['attr'])) {
            $view->vars['attr'] = array();
        }
        
        $view->vars['attr'] = array_merge($view->vars['attr'], array(
            'data-week-start'             =>  $options['week_start'],
            'data-calendar-weeks'         =>  json_encode($options['calendar_weeks']),
            'data-start-date'             =>  $options['start_date'],
            'data-end-date'               =>  $options['end_date'],
            'data-days-of-week-disabled'  =>  $options['days_of_week_disabled'],
            'data-autoclose'              =>  json_encode($options['autoclose']),
            'data-start-view'             =>  $options['start_view'],
            'data-min-view-mode'          =>  $options['min_view_mode'],
            'data-today-btn'              =>  $today_btn,
            'data-today-highlight'        =>  json_encode($options['today_highlight']),
            'data-clear-btn'              =>  json_encode($options['clear_btn']),
            'data-language'               =>  $language,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'input'                 =>  'datetime',
            'format'                =>  'yyyy-MM-dd',
            'week_start'            =>  1,
            'calendar_weeks'        =>  false,
            'start_date'            =>  date('Y-m-d', strtotime('-20 years')),
            'end_date'              =>  date('Y-m-d', strtotime('+20 years')),
            'days_of_week_disabled' =>  '',
            'autoclose'             =>  true,
            'start_view'            =>  0,
            'min_view_mode'         =>  0,
            'today_btn'             =>  false,
            'today_highlight'       =>  false,
            'clear_btn'             =>  false,
            'language'              =>  false,
            'attr'                  =>  array(
                'class'   =>  'input-small'
            ),
        ));

        $resolver->setAllowedValues(array(
            'week_start'      => range(0, 6),
            'start_view'      => array(0, 'month', 1, 'year', 2, 'decade'),
            'min_view_mode'   => array(0, 'days', 1, 'months', 2, 'years'),
            'calendar_weeks'  => array(true, false),
            'autoclose'       => array(true, false),
            'today_btn'       => array(true, false, 'linked'),
            'today_highlight' => array(true, false),
            'clear_btn'       => array(true, false),
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'date_picker';
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