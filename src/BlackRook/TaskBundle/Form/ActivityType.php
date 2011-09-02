<?php

namespace BlackRook\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('task', 'entity', array(
                'class' => 'BlackRook\TaskBundle\Entity\Task',
                'property' => 'name',
                'required' => false
            ))
            ->add('startedAt', 'datetime', array(
                                                'required' => false,
                                                'label' => 'Start at',
                                                'widget' => 'single_text',
                                                'attr' => array(
                                                    'class' => 'date'
                                                )
                                            ))
            ->add('description', 'textarea', array('required' => false, 'label' => 'Description'))
            ->add('duration', 'integer', array('required' => false, 'label' => 'Duration to date'))
            // ->add('user', 'entity', array(
            //     'class' => 'BlackRook\TaskBundle\Entity\User',
            //     'property' => 'username',
            //     'required' => false
            // ))
        ;
    }

    public function getName()
    {
        return 'blackrook_taskbundle_activitytype';
    }
}
