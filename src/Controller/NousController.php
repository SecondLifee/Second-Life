<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NousController extends AbstractController
{
    /**
     * @Route("/nous", name="propos_nous")
     * @return Response
     */

    public function us()
    {
        return $this->render('front/nous.html.twig');
    }



}

