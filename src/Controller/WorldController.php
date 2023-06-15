<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WorldController extends AbstractController
{
    public function __construct(private readonly UserManager $userManager)
    {
    }

    public function hello(): Response
    {
        $user = $this->userManager->create('Terry Pratchett');
        sleep(1);
        $this->userManager->updateUserLogin($user->getId(), 'Lewis Carroll');

        return $this->json($user->toArray());
    }
}
