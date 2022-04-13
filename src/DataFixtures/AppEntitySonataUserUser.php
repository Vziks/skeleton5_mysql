<?php

namespace App\DataFixtures;

use App\Entity\SonataUserUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppEntitySonataUserUser extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $moderator = $this->getAdmin();
        $manager->persist($moderator);

        $manager->flush();
    }

    public function getAdmin(): SonataUserUser
    {

        $user = new SonataUserUser();

        $user->setEmail('admin');
        $user->setUsername('admin');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setSuperAdmin(true);

        return $user;
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}
