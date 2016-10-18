<?php

namespace Udec\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('primerNombre')
            ->add('segundoNombre')
            ->add('primerApellido')
            ->add('segundoApellido')
            ->add('codigo')
            ->add('email')
            ->add('telefonoFijo')
            ->add('movil')
            ->add('tipoDoc')
            ->add('numeroDoc')
            ->add('estado')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Udec\AppBundle\Entity\Personas'
        ));
    }
}
