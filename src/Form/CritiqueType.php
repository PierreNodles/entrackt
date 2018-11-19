<?php

namespace App\Form;

use App\Entity\Critique;
use App\Entity\Movie;

use App\Repository\MoviesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CritiqueType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
    ->add('title')
    ->add('image', FileType::class)
    ->add('critique')
    ->add('movie', EntityType::class, array(
      // looks for choices from this entity
      'class' => Movie::class,

      'query_builder' => function (MoviesRepository $er) {
        return $er->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC');
    },

      // uses the User.username property as the visible option string
      'choice_label' => 'name',

      // used to render a select box, check boxes or radios
      // 'multiple' => true,
      // 'expanded' => true,
    ))
    ->add('submit', SubmitType::class, array('label' => 'Poster la critique'))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Critique::class,
    ]);
  }
}
