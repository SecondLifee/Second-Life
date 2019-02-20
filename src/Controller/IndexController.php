<?php

namespace App\Controller;


use App\Entity\Annonce;
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

    public function index() {
        return $this->render('front/annonce.html.twig');
    }
    /**
     * @Route("/categorie/{slug<[a-zA-Z0-9\-_\/]+>}",
     *     methods={"GET"},
     *     defaults={"slug":"latest"},
     *     name="categorie_home")
     * @param $slug
     * @return Response
     */
    public function categorie($slug)
    {

        $categorie = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findOneBy(['slug' => $slug]);

        $annonces = $categorie->getAnnonces();

        # return new Response("<h1>PAGE CATEGORIE : $slug</h1>");
        return $this->render('front/impo.html.twig', [
            'annonces' => $annonces,
            'categorie' => $categorie
        ]);
    }


    }