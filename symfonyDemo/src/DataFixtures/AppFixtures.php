<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = [];
        // $product = new Product();
        // $manager->persist($product);


        $manager->flush();
    }


    /**
     * @return string[]

    public static function getGroups(ObjectManager $manager): array {

        function rand_except($min, $max, $excepting = array()) {

            $num = mt_rand($min, $max);

            return in_array($num, $excepting) ? rand_except($min, $max, $excepting) : $num;
        }
        for ($j =0; $j=rand_except(0,20,[5,6]); $j++) {
            $user =  new User();
            $user ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'password'));
            $manager->persist($user);
        }
        for ($i = 0; $i<mt_rand(13,25); $i++) {
            $post = new Article();
            for ($j =0; $j=rand_except(0,10,[5,6]); $j++) {
                $like = new ArticleLike();
                $like->setArticle($post)
                    ->getUser()
                    ->setCreatedAt();
            }
            $like = new ArticleLike();
            $post = new Article();

        }

    }
    */
}
