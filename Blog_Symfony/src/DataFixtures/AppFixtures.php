<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername("Michelle")
             ->setEmail("user@michelle.com")
             ->setPassword("12345678")
             ->setRole("ROLE_USER");

        $manager->persist($user);

        for($i = 1; $i <=4 ; $i++){

            $article = new Article();

            $article->setTitre("Nom de l'article n°$i")
                    ->setContenue("Ac turpis egestas integer eget aliquet nibh praesent tristique. Duis ultricies lacus sed turpis tincidunt. Maecenas ultricies mi eget mauris pharetra. Turpis egestas integer eget aliquet nibh praesent tristique.")
                    ->setPicture("https://placehold.it/300x300")
                    ->setAuthor("Michelle");

            $manager->persist($article);
        }

        $manager->flush();
    }
}
