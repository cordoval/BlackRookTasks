<?php

namespace BlackRook\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
			->add('tasks', 'collection', array( 
					"type" => new TaskType(),
					"allow_add" => true,
					"prototype" => true,
					"allow_delete" => true
			))
		;
    }

    public function getName()
    {
        return 'blackrook_taskbundle_projecttype';
    }
}
