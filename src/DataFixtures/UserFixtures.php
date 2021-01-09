<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE_ONE = 'user-reference-one';
    public const USER_REFERENCE_TWO = 'user-reference-two';
    public const USER_REFERENCE_THREE = 'user-reference-three';
    public const USER_REFERENCE_FOUR = 'user-reference-four';
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setUsername('User' . $i);
            $plainPassword = 'test';
            $user->setPassword($this->encoder->encodePassword($user,$plainPassword));
            $user->setEmail('email' . $i . '@mail.com');
            switch ($i) {
                case 1:
                    $user->setUsername('Admin');
                    $this->addReference(self::USER_REFERENCE_ONE, $user);
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 2:
                    $this->addReference(self::USER_REFERENCE_TWO, $user);
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 3:
                    $this->addReference(self::USER_REFERENCE_THREE, $user);
                    $user->setRoles(['ROLE_USER']);
                    break;
                case 4:
                    $this->addReference(self::USER_REFERENCE_FOUR, $user);
                    $user->setRoles(['ROLE_USER']);
                    break;
                case 5:
                    $user->setRoles(['ROLE_USER']);
                    break;
            }
            $manager->persist($user);

        }
        $manager->flush();
    }
}
