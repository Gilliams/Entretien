<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        $faker = Factory::create();
        
        for($count = 0; $count < 5; $count++){

            $user = new User();
            $user->setEmail($faker->freeEmail);
            
            $password = $this->encoder->encodePassword($user, 'secret');
            $user->setPassword($password);
            $username = $faker->name;
            $user->setUsername($username);
            
            for($num = 0; $num < mt_rand(1,4); $num++){
                $article = new Article();
                $article->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
                $article->setContent($faker->text);
                $article->setCreatedAt($faker->dateTime);
                $article->setSlug($slugify->slugify($article->getTitle()));
                $article->setImageName('https://picsum.photos/800/600');
                $article->setAuthor($user);
    
                $manager->persist($article);
            }

            for($i = 0; $i < mt_rand(1,3); $i++){
                $comment = new Comment();
                $comment->setMessage($faker->text);
                $comment->setAuthorComment($user);
                $comment->setCommentArticle($article);
    
                $manager->persist($comment);
            }


            $manager->persist($user);
        }
        $manager->flush();
    }

}
