<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Api;


class AddApi extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
    ->add('movie_number', IntegerType::class, array(
       'label' => 'Nombre de films à ajouter',
    ))
    ->add('key_api', TextType::class, array(
       'label' => 'Clef API',
    ))

    ->add('submit', SubmitType::class, array('label' => 'Soumettre la requête'))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
     $resolver->setDefaults(array(
        'data_class' => Api::class,
     ));
  }
}
