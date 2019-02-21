<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     * @return Response
     */

    public function profil()
    {
        return $this->render('Profil/profil.html.twig');
    }
}