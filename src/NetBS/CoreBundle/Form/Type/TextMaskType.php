<?php

namespace NetBS\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextMaskType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('mask');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['mask'] = $options['mask'];
    }

    public function getParent()
    {
        return TextType::class;
    }
}