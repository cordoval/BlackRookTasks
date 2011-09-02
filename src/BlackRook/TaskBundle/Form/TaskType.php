<?php

namespace BlackRook\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TaskType extends AbstractType
{
    private static $fields = array(
        'id' => array('hidden', array('required' => false)),
        'name' => array('text', array('required' => false, 'label' => 'Task')),
        'description' => array('textarea', array('required' => false, 'label' => 'Description')),
        'estimatedDuration' => array('integer',  array('required' => false, 'label' => 'Estimated duration')),
        // 'completedAt' => array('datetime',  array('required' => false, 'label' => 'Completed At')),
        'completedAt' => array('datetime',  array(
                                    'required' => false,
                                    'label' => 'Completed At',
                                    'widget' => 'single_text',
                                    'attr' => array(
                                        'class' => 'date'
                                    )
                                )),
        'dueAt' => array('datetime',  array(
                                        'required' => false,
                                        'label' => 'Due date',
                                        'widget' => 'single_text',
                                        'attr' => array(
                                            'class' => 'date'
                                        )
                                )),
        'startAt' => array('datetime',  array(
                                            'required' => false,
                                            'label' => 'Start on',
                                            'widget' => 'single_text',
                                            'attr' => array(
                                                'class' => 'date'
                                            )
                                        )),
        'parent' => array('entity', array(
            'class' => 'BlackRook\TaskBundle\Entity\Task',
            'property' => 'name',
            'required' => false,
            'label' => 'Parent Task'
        )),
        'project' => array('entity', array(
            'class' => 'BlackRook\TaskBundle\Entity\Project',
            'property' => 'name',
            'required' => false,
            'label' => 'Parent Project'
        )),
        'assignees' => array('entity', array(
            'class' => 'BlackRook\TaskBundle\Entity\User',
            'property' => 'username',
            'required' => false,
            'label' => 'Assignee',
            'multiple' => true
        )),
    );

    public function buildForm(FormBuilder $builder, array $options)
    {
        foreach (self::$fields as $field_id => $field_options) {
            $builder->add($field_id, $field_options[0], $field_options[1]);
        }
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'BlackRook\TaskBundle\Entity\Task',
        );
    }

    public function getName()
    {
        return 'blackrook_taskbundle_tasktype';
    }

}