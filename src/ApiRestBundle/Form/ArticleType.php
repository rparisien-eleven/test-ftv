<?php

namespace ApiRestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', ['required' => true, 'label' => 'Titre*: '])
            ->add('leading', 'textarea', ['required' => false, 'label' => 'Accroche: '])
            ->add('body', 'textarea', ['required' => false, 'label' => 'Corps: '])
            ->add('slug', 'text', ['required' => true])
            ->add('createdAt', 'datetime', [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd'
                ]
            )
            ->add('createdBy', 'text', ['required' => true, 'label' => 'Auteur*: '])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => 'ApiRestBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'apirestbundle_article';
    }
}
