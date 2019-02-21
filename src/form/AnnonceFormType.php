<?php


namespace App\Form;


use App\Entity\Annonce;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array  $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
                'label' => "Titre de l'Annonce",
                'attr' => [
                    'placeholder' =>"Titre de l'Annnonce"
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false,
                'label' => false
            ])
            ->add('contenu', TextareaType::class, [
                'label' => false
            ])
            ->add('featuredImage', FileType::class, [
                'attr' => [
                    'class' => 'dropify'
                ]
            ])
            ->add('services',ChoiceType::class, [
                'choices' => [
                    'Negocier' => 'Negocier',
                    'Vendre' => 'Vendre',
                    'Donner' => 'Donner'


                ],
            ])

            ->add('departement',TextType::class, [
                'label' => "Département"
            ])
            ->add('spotlight', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'data-toogle' => 'toogle',
                    'data-on' => 'oui',
                    'data-off' => 'non'

                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier mon Annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        # Mon formulaire s'attent à recevoir une instance de Annonce.
        # Tout autres instances ne fonctionnera pas.
        $resolver->setDefault('data_class', Annonce::class);
    }

    /**
     * Permet de prefixer les champs de mon formulaire.
     * Ex. prefix_nomduchamp
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'form';
    }
}

















