<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Movie;

class AddMovie extends AbstractType

{
   public function buildForm(FormBuilderInterface $builder, array $options)
   {
      $builder
      ->add('name', TextType::class, array(
         'label' => 'Titre du film',
      ))
      ->add('slug', TextType::class, array(
         'required' => false,
      ))
      ->add('synopsis', TextareaType::class)
      ->add('submit', SubmitType::class, array('label' => 'Soumettre le film'))
      ;
   }

   public function configureOptions(OptionsResolver $resolver)
   {
      $resolver->setDefaults(array(
         'data_class' => Movie::class,
      ));
   }
}
