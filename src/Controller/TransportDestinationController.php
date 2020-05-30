<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TransportDestinationController extends AbstractController
{
    /**
     * @Route("/transport/destination", name="transport_destination")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TransportDestinationController.php',
        ]);
    }
}
