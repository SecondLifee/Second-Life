<?php
/**
 * Created by PhpStorm.
 * User: legou
 * Date: 14/02/2019
 * Time: 10:28
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnecFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
          ->add('email', EmailType::class, [
              'label' => false,
              'attr' => ['placeholder' => 'Rentrez votre Email.']
          ])
          ->add('password',PasswordType::class, [
              'label' => false,
              'attr' => ['placeholder' => 'Rentrez votre mot de passe']
          ])
          ->add('submit', SubmitType::class, [
              'label' => 'Connectez vous.'
          ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', null);
    }

    public function getBlockPrefix()
    {
        return 'app_login';
    }
}