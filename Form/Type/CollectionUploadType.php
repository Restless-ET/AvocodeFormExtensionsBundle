<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Avocode\FormExtensionsBundle\Form\EventListener\CollectionUploadSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Avocode\FormExtensionsBundle\Storage\FileStorageInterface;

/**
 * See `Resources/doc/collection-upload/overview.md` for documentation
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class CollectionUploadType extends AbstractType
{
    /**
     * @var FileStorageInterface
     */
    protected $storage = null;

    /**
     * @param FileStorageInterface $fileStorage
     */
    public function setFileStorage(FileStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CollectionUploadSubscriber(
            $builder->getName(),
            $options,
            $this->storage
        ));

        if (!$builder->hasAttribute('prototype')) {
            $prototype = $builder->create($options['prototype_name'], $options['type'], array_replace(array(
                'label' => $options['prototype_name'].'label__',
            ), $options['options']));
            $builder->setAttribute('prototype', $prototype->getForm());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge(
            $view->vars,
            array(
                'acceptFileTypes'           => $options['acceptFileTypes'],
                'autoUpload'                => $options['autoUpload'],
                'editable'                  => $options['editable'],
                'loadImageFileTypes'        => $options['loadImageFileTypes'],
                'loadImageMaxFileSize'      => $options['loadImageMaxFileSize'],
                'maxNumberOfFiles'          => $options['maxNumberOfFiles'],
                'maxFileSize'               => $options['maxFileSize'],
                'minFileSize'               => $options['minFileSize'],
                'multipart'                 => $options['multipart'],
                'multiple'                  => $options['multiple'],
                'nameable'                  => $options['nameable'],
                'nameable_field'            => $options['nameable_field'],
                'novalidate'                => $options['novalidate'],
                'prependFiles'              => $options['prependFiles'],
                'previewFilter'             => $options['previewFilter'],
                'previewAsCanvas'           => $options['previewAsCanvas'],
                'previewMaxHeight'          => $options['previewMaxHeight'],
                'previewMaxWidth'           => $options['previewMaxWidth'],
                'primary_key'               => $options['primary_key'],
                'required'                  => $options['required'],
                'sortable'                  => $options['sortable'],
                'sortable_field'            => $options['sortable_field'],
                'uploadRouteName'           => $options['uploadRouteName'],
                'uploadRouteParameters'     => $options['uploadRouteParameters']
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'acceptFileTypes'           => '/.*$/i',
            'autoUpload'                => false,
            'editable'                  => array(),
            'loadImageFileTypes'        => '/^image\/(gif|jpe?g|png)$/i',
            'loadImageMaxFileSize'      => 5000000,
            'maxNumberOfFiles'          => null,
            'maxFileSize'               => null,
            'minFileSize'               => null,
            'multipart'                 => true,
            'multiple'                  => true,
            'nameable'                  => true,
            'nameable_field'            => 'name',
            'novalidate'                => true,
            'prependFiles'              => false,
            'previewAsCanvas'           => true,
            'previewFilter'             => null,
            'previewMaxHeight'          => 80,
            'previewMaxWidth'           => 80,
            'primary_key'               => 'id',
            'required'                  => false,
            'sortable'                  => false,
            'sortable_field'            => 'position',
            'uploadRouteName'           => null,
            'uploadRouteParameters'     => array()
        ));

        // This seems weird... why to we accept it as option if we force
        // its value?
        $resolver->setAllowedValues('novalidate', array(true));
        $resolver->setAllowedValues('multipart', array(true));
        $resolver->setAllowedValues('multiple', array(true));
        $resolver->setAllowedValues('required', array(false));

        $resolver->setAllowedTypes('acceptFileTypes', array('string'));
        $resolver->setAllowedTypes('autoUpload', array('bool'));
        $resolver->setAllowedTypes('editable', array('array'));
        $resolver->setAllowedTypes('loadImageFileTypes', array('string'));
        $resolver->setAllowedTypes('loadImageMaxFileSize', array('integer'));
        $resolver->setAllowedTypes('maxNumberOfFiles', array('integer', 'null'));
        $resolver->setAllowedTypes('maxFileSize', array('integer', 'null'));
        $resolver->setAllowedTypes('minFileSize', array('integer', 'null'));
        $resolver->setAllowedTypes('multipart', array('bool'));
        $resolver->setAllowedTypes('multiple', array('bool'));
        $resolver->setAllowedTypes('nameable', array('bool'));
        $resolver->setAllowedTypes('nameable_field', array('string', 'null'));
        $resolver->setAllowedTypes('novalidate', array('bool'));
        $resolver->setAllowedTypes('prependFiles', array('bool'));
        $resolver->setAllowedTypes('previewAsCanvas', array('bool'));
        $resolver->setAllowedTypes('previewFilter', array('string', 'null'));
        $resolver->setAllowedTypes('previewMaxWidth', array('integer'));
        $resolver->setAllowedTypes('previewMaxHeight', array('integer'));
        $resolver->setAllowedTypes('primary_key', array('string'));
        $resolver->setAllowedTypes('required', array('bool'));
        $resolver->setAllowedTypes('sortable', array('bool'));
        $resolver->setAllowedTypes('sortable_field', array('string'));
        $resolver->setAllowedTypes('uploadRouteName', array('string', 'null'));
        $resolver->setAllowedTypes('uploadRouteParameters', array('array'));
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
    public function getName()
    {
        return 'afe_collection_upload';
    }
}
