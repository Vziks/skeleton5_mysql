<?php

namespace App\Security;

use App\Entity\UserAdmin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername($email): UserAdmin
    {
        $user = $this->findOneUserBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException(
                sprintf(
                    'User with "%s" email does not exist.',
                    $email
                )
            );
        }

        return $user;
    }

    private function findOneUserBy(array $options): ?UserAdmin
    {
        return $this->entityManager
            ->getRepository(UserAdmin::class)
            ->findOneBy($options);
    }

    public function refreshUser(UserInterface $user): UserAdmin
    {
        assert($user instanceof UserAdmin);

        if (null === $reloadedUser = $this->findOneUserBy(['id' => $user->getId()])) {
            throw new UserNotFoundException(
                sprintf(
                    'User with ID "%s" could not be reloaded.',
                    $user->getId()
                )
            );
        }

        return $reloadedUser;
    }

    public function supportsClass($class): bool
    {
        return $class === UserAdmin::class;
    }
}