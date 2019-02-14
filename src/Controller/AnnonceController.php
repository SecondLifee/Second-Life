<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Membre;
use App\form\AnnonceFormType;
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
        # Récuperation d'un membre
        # Ici doctrine me retourne un objet Membre
        $membre = $this->getDoctrine()
            ->getRepository(Membre::class)
            ->find(1);

        # Création d'une Nouvelle Annonce
        $annonce = new Annonce();

        # je déclare l'auteur de l'Annonce
        $annonce->setMembre($membre);

        # création du formulaire
        $form = $this->createForm(AnnonceFormType::class, $annonce);

        # traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            # Traitement de l'upload de l'image
            /** @var UploadedFile $featuredImage */
            $featuredImage = $annonce->getFeaturedImage();

            # Renommer le nom du fichier
            $fileName = $this->slugify($annonce->getTitre())
                . '.' . $featuredImage->guessExtension();

            # Deplacer le fichier vers son répertoire final
            $featuredImage->move(
                $this->getParameter('annonces_assets_dir'),
                $fileName
            );

            # Mise à jour de l'image
            $annonce->setFeaturedImage($fileName);

             #Mise à jour du Slug
            $annonce->setSlug($this->slugify($annonce->getTitre()));

            # Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            # Redirection
            return $this->redirectToRoute('front_annonce', [
                'categorie' => $annonce->getCategorie()->getSlug(),
                'slug' => $annonce->getSlug(),
                'id' => $annonce->getId()
            ]);

        }

        # Passage à la vue du formulaire
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
        # Récupérer l'Article en BDD
        $annonce = $this->getDoctrine()
            ->getRepository(Annonce::class)
            ->find($id);

        # Récupérer la featuredImage
        $featuredImage = $annonce->getFeaturedImage();

        # Création du Formulaire
        $annonce->setFeaturedImage(
            new File($this->getParameter('annonces_assets_dir')
                . '/' . $annonce->getFeaturedImage())
        );

        $form = $this->createForm(Annonce::class, $annonce)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($annonce->getFeaturedImage() != null) {

                # Traitement de l'upload de l'image
                /** @var UploadedFile $featuredImage */
                $featuredImage = $annonce->getFeaturedImage();

                # Renommer le nom du fichier
                $fileName = $this->slugify($annonce->getTitre())
                    . '.' . $featuredImage->guessExtension();

                # Deplacer le fichier vers son répertoire final
                $featuredImage->move(
                    $this->getParameter('annonces_assets_dir'),
                    $fileName
                );

                # Mise à jour de l'image
                $annonce->setFeaturedImage($fileName);

            } else {

                $annonce->setFeaturedImage($featuredImage);

            }

            # Mise à jour du Slug
            $annonce->setSlug($this->slugify($annonce->getTitre()));

            # Sauvegarde en BDD
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
