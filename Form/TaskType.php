<?php

namespace Alt87\Bundle\TodoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'nom de la tache'
            ))
            ->add('complete', 'checkbox', array(
                'label' => 'est terminée',
                'required' => FALSE
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Alt87\Bundle\TodoBundle\Entity\Task'
        ));
    }

    public function getName()
    {
        return 'alt87_bundle_todobundle_tasktype';
    }
}
