<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher =$hasher;
    }
    public function load(ObjectManager $manager)
    {
        $typesRole =['ROLE_MANAGER', 'ROLE_ADMIN', 'ROLE_USER'];
        $cpt=1;

        foreach($typesRole as $type){

            $user = new User();
            $username= 'User'.$cpt;
            $user->setUsername($username);
            $password= 'Password'.$cpt;
            $user->setPassword($this->hasher->hashPassword($user, $password));
           
            $user->setRoles([$type]);
            $manager->persist($user);
            $cpt ++;

        }
        $manager->flush();
    }
}