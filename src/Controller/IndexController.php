<?php

namespace App\Controller;


use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/home", name="index_home")
     */

    public function index()
    {
        return $this->render('front/accueil.html.twig');
    }

    /**
     * @Route("/categorie/{slug<[a-zA-Z0-9\-_\/]+>}",
     *     methods={"GET"},
     *     defaults={"slug":"latest"},
     *     name="categorie_home")
     * @param Categorie $categorie
     * @return Response
     */
    public function categorie(Categorie $categorie)
    {
        $annonces = $categorie->getAnnonces();
        return $this->render('front/impo.html.twig', [
            'anns' => $annonces,
            'categorie' => $categorie
        ]);
    }
}
