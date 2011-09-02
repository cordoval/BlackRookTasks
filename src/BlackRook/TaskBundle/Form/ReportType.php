<?php

namespace BlackRook\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('description', 'textarea')
            ->add('facts', 'yamlToArray', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'blackrook_taskbundle_reporttype';
    }
}
