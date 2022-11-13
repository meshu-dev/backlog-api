<?php

namespace App\DataFixtures;

use App\Repository\UserRepository;
use App\Entity\User;

class UserFixture extends BaseFixture
{
    public function __construct(protected UserRepository $userRepository) { }

    public function loadData()
    {
        $user = new User();
        $user->setEmail('harmeshuppal@gmail.com');

        $hashedPassword = $this->userRepository->hashPassword($user, 'testtest');
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);
    }
}
