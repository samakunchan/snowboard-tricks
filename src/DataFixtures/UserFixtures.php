<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $name = ['Sam', 'Jean', 'Pierre', 'Paul', 'Marie', 'Jeanne', 'Anna', 'Luc', 'Joe', 'Suzie', 'Alan', 'Carol', 'Sonia', 'khaled', 'Younes', 'Kobe', 'John', 'Tracy'];
        for ($i = 0; $i < count($name); $i++) {
            $user = new User();
            $user->setFirstname($name[$i]);
            $user->setLastname($name[$i]);
            $user->setEmail(strtolower($name[$i].'@test.fr'));
            $hash = $this->encoder->encodePassword($user, strtolower($name[$i]));
            $user->setPassword($hash);
            if ($name[$i] === 'Sam'){
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }
            try {
                $user->setCreatedAt(new DateTime('now'));
            } catch (Exception $e) {
            }
            $manager->persist($user);
        }

        $manager->flush();
    }
}
