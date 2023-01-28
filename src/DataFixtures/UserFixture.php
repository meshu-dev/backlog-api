<?php

namespace App\DataFixtures;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use App\Repository\UserRepository;
use App\Entity\User;

class UserFixture extends BaseFixture implements FixtureGroupInterface
{
    public function __construct(
        protected ContainerBagInterface $containerBag,
        protected UserRepository $userRepository
    ) { }

    public function loadData()
    {
        $email = $this->containerBag->get('app.user_email');
        $password = $this->containerBag->get('app.user_password');

        $user = new User();
        $user->setEmail($email);

        $hashedPassword = $this->userRepository->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);
    }

    public static function getGroups(): array
    {
        return ['live', 'test'];
    }
}
