<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Membre;
use App\Form\AnnonceFormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    use HelperTrait;

    /**
     * @Route("/creer-une-annonce", name="annonce_new")
     * @param Request $request
     * @return Response
     */
    public function newAnnonce(Request $request)
    {

        $membre = $this->getDoctrine()
            ->getRepository(Membre::class)
            ->find(1);

        $annonce = new Annonce();

        $annonce->setMembre($membre);

        $form = $this->createForm(AnnonceFormType::class, $annonce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $featuredImage */
            $featuredImage = $annonce->getFeaturedImage();

            $fileName = $this->slugify($annonce->getTitre())
                . '.' . $featuredImage->guessExtension();

            $featuredImage->move(
                $this->getParameter('annonces_assets_dir'),
                $fileName
            );

            $annonce->setFeaturedImage($fileName);

            $annonce->setSlug($this->slugify($annonce->getTitre()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('front_annonce', [
                'categorie' => $annonce->getCategorie()->getSlug(),
                'slug' => $annonce->getSlug(),
                'id' => $annonce->getId()
            ]);

        }

        return $this->render('front/form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/editer-une-annonce/{id<\d+>}", name="annonce-edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAnnonce($id, Request $request)
    {
        $annonce = $this->getDoctrine()
            ->getRepository(Annonce::class)
            ->find($id);

        $featuredImage = $annonce->getFeaturedImage();

        $annonce->setFeaturedImage(
            new File($this->getParameter('annonces_assets_dir')
                . '/' . $annonce->getFeaturedImage())
        );

        $form = $this->createForm(Annonce::class, $annonce)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($annonce->getFeaturedImage() != null) {

                /** @var UploadedFile $featuredImage */
                $featuredImage = $annonce->getFeaturedImage();

                $fileName = $this->slugify($annonce->getTitre())
                    . '.' . $featuredImage->guessExtension();

                $featuredImage->move(
                    $this->getParameter('annonces_assets_dir'),
                    $fileName
                );

                $annonce->setFeaturedImage($fileName);

            } else {

                $annonce->setFeaturedImage($featuredImage);

            }

            $annonce->setSlug($this->slugify($annonce->getTitre()));

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            # Redirection
            return $this->redirectToRoute('front_form', [
                'categorie' => $annonce->getCategorie()->getSlug(),
                'slug' => $annonce->getSlug(),
                'id' => $annonce->getId()
            ]);
        }

        return $this->render('membre/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }






}
