<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre de votre article'
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu de l'article"
            ])
            ->add('imageFile', VichImageType::class, [
                "label" => "Image (JPG or PNG file)",
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
