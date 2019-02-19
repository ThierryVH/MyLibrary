<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, array(
                'choices' => $this->getChoices(),
                'label' => 'Filtrer par catégorie :'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'csrf_protection' => false,
            'method' => 'get'
        ]);
    }

    public function getChoices()
    {
        $choices = $this->categoryRepository->findAll();
        // dump($this->categoryRepository->findAll());

        $output = [
            'Toutes Catégories' => null
        ];

        foreach ($choices as $key => $value) {
            $output[$value->getName()] = $value->getId();
        }

        return $output;
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
