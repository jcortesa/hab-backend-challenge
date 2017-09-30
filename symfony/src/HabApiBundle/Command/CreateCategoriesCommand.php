<?php

namespace HabApiBundle\Command;

use HabApiBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCategoriesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('hab-api:create-categories')
        ->setDescription('Creates categories from provided data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $categoriesData = [
            [
                'name' => 'construcción',
                'slug' => 'construccion',
                'children' => [
                    [
                        'name' => 'construcción casas',
                        'slug' => 'construccion-casas'
                    ],
                    [
                        'name' => 'construcción edificios',
                        'slug' => 'construccion-edificios'
                    ],
                    [
                        'name' => 'construcción piscinas',
                        'slug' => 'construccion-piscinas'
                    ],
                ],
            ],
            [
                'name' => 'reformas',
                'slug' => 'reformas',
                'children' => [
                    [
                        'name' => 'reforma baños',
                        'slug' => 'reforma-banos'
                    ],
                    [
                        'name' => 'reforma cocinas',
                        'slug' => 'reforma-cocinas'
                    ],
                    [
                        'name' => 'reforma integral',
                        'slug' => 'reforma-integral'
                    ],
                ],
            ],
            [
                'name' => 'instaladores',
                'slug' => 'instaladores',
                'children' => [
                    [
                        'name' => 'calefacción',
                        'slug' => 'calefaccion'
                    ],
                    [
                        'name' => 'aire acondicionado',
                        'slug' => 'aire-acondicionado'
                    ],
                ],
            ]
        ];

        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach ($categoriesData as $categoryData) {
            if ($this->doCategoryExists($categoryData['slug'], $em)) {
                continue;
            }

            $category = new Category();
            $category
                ->setName($categoryData['name'])
                ->setSlug($categoryData['slug'])
            ;
            $em->persist($category);

            if (isset($categoryData['children']) && !empty($categoryData['children'])) {
                foreach ($categoryData['children'] as $childData) {
                    if ($this->doCategoryExists($childData['slug'], $em)) {
                        continue;
                    }

                    $childCategory = new Category();
                    $childCategory
                        ->setName($childData['name'])
                        ->setSlug($childData['slug'])
                    ;

                    $childCategory->setParent($category);
                    $em->persist($childCategory);
                }

                $em->flush();
            }

            $em->flush();
        }

        $output->writeln('Categories added! 🚀');
    }

    private function doCategoryExists($slug, $em)
    {

        $category = $em
            ->getRepository('HabApiBundle:Category')
            ->findOneBy(['slug' => $slug]);

        return $category instanceof Category;
    }
}
