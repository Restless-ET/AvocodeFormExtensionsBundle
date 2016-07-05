<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * See `Resources/doc/elastic-textarea/overview.md` for documentation
 *
 * @author Pierrick VIGNAND <pierrick.vignand@gmail.com>
 */
class ElasticTextareaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        // BC for Symfony < 3
        if (!method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return 'textarea';
        }

        return 'Symfony\Component\Form\Extension\Core\Type\TextareaType';
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
        return 'afe_elastic_textarea';
    }
}
