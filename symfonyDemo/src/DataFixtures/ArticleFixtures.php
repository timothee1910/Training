<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        // Creation de 3 catégorie faker
        for ($i=1; $i<=3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());
            $manager->persist($category);
            // Pour chaque catégory crées On crée entre 4 et 6 article
            for ($j = 1; $j <= mt_rand(4,6); $j++) {
                $article = new Article();
                $content = '<p>' .join($faker->paragraphs(3),'</p><p>'). '</p>';
                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 month','now'))
                    ->setCategory($category);
                $manager->persist($article);

                /**
                 * Pour chaque article on crée 4 à 10 commentaires
                 * join = implode ici
                 */
                for ($k = 1; $k<= mt_rand(4, 10); $k++) {
                    $comment =  new Comment();
                    $content = '<p>' .join($faker->paragraphs(2),'</p><p>'). '</p>';
                    $now = new \DateTime();
                    /**
                     * Datetime::diff() retourne la difference entre deux objets datetime
                     */
                    $interval = $now->diff($article->getCreatedAt());
                    //pour connaitre le nombre de jours de l'interval
                    $days = $interval->days;
                    $min = '-'.$days.'days'; //-100days par exemple Cela nous donne la date de création (en jours) de l'article

                    $comment->setAuthor($faker->name())
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($min)) //mettre une date entre il y a x jours et maintenant
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
        //permet l'insertion dans la bdd après le persist
        $manager->flush();
    }
}
