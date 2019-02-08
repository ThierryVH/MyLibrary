<?php

namespace App\Form;

use App\Entity\Book;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Repository\CategoryRepository;

class BookType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('author')
            ->add('release_date')
            ->add('summary')
            ->add('category', ChoiceType::class, array(
                'choices' => $this->getChoices()
            ))
            // On ajoute les champs liés à l'ajout d'une image
            ->add('image', ImageType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'translation_domain' => 'forms'
        ]);
    }

    public function getChoices()
    {
        $choices = $this->categoryRepository->findAll();
        // dump($this->categoryRepository->findAll());

        $output = [];

        foreach ($choices as $key => $value) {
            $output[$value->getName()] = $value;
        }

        return $output;
    }
}
