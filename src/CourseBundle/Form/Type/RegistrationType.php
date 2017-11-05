<?php
namespace CourseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => true, 'label' => 'Jméno:'));
        $builder->add('surname', 'text', array('required' => true, 'label' => 'Příjmení:'));

        parent::buildForm($builder, $options);
    }


    public function getDefaultOptions(array $options)
    {
        $options['data_class'] = 'CourseBundle\Form\Model\CourseRegistration';

        return $options;
    }

    public function getName()
    {
        return 'coursebundle_course_registration';
    }

}