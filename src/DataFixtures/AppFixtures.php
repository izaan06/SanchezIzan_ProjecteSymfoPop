<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Genera dades de prova per a l'aplicació (usuaris i productes).
 */
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Utilitzem Faker per generar dades realistes
        $faker = Factory::create();
        $users = [];

        // Crea 5 Usuaris de prova
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setName($faker->name());
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->hasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        // Crea 20 Productes aleatoris
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setTitle(ucfirst($faker->words(3, true)));
            $product->setDescription($faker->paragraph());
            $product->setPrice($faker->randomFloat(2, 5, 500));
            $product->setImage('https://picsum.photos/seed/' . $i . '/600/400');
            $product->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));

            // Assigna un propietari aleatori dels usuaris creats
            $product->setOwner($users[array_rand($users)]);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
