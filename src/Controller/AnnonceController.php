<?php

namespace App\Controller;

use App\Entity\Annonce;
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
        #Récupération d'un membre
        $membre = $this->getUser();

      # Création d'une nouvelle annonce
        $annonce = new Annonce();

        #Je déclare l'auteur de l'annonce
        $annonce->setMembre($membre);
        #Création du formulaire
        $form = $this->createForm(AnnonceFormType::class, $annonce);
        #Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            #Traitement de l'upload de l'image
            /** @var UploadedFile $featuredImage */

            $featuredImage = $annonce->getFeaturedImage();

            #Renomer le nom du fichier
            $fileName = $this->slugify($annonce->getTitre())
                . '.' . $featuredImage->guessExtension();
            #Déplace le fichier vers son répertoire final.
            $featuredImage->move(
                $this->getParameter('annonces_assets_dir'),
                $fileName
            );
           #Mis à jour de l'image
            $annonce->setFeaturedImage($fileName);

            #Mis à jour du slug
            $annonce->setSlug($this->slugify($annonce->getTitre()));

            #Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            # Redirection
            return $this->redirectToRoute('profil', [
                'categorie' => $annonce->getCategorie()->getSlug(),
                'slug' => $annonce->getSlug(),
                'id' => $annonce->getId()
            ]);
        }

        # Passage à la vue du formulaire
        return $this->render('annonce/formcreer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editer-une-annonce/{id}",
     *     name="annonce-edit")
     * @param Request $request
     * @param $id
     * @return Response
     */

    public function editAnnonce(Request $request, $id)
    {
        # Récupérer l'Annonce en BDD
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

        $form = $this->createForm(AnnonceFormType::class, $annonce)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $featuredImage */
            if ($annonce->getFeaturedImage() != null) {
                # Traitement de l'upload de l'image
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
            return $this->redirectToRoute('profil', [
                'categorie' => $annonce->getCategorie()->getSlug(),
                'slug' => $annonce->getSlug(),
                'id' => $annonce->getId()
            ]);
        }
        return $this->render('annonce/formmodifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimer-une-annonce/{id}",
     *     name="annonce-delete")
     * @param Annonce $annonce
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    # Suppression annonce
    public function delete(Annonce $annonce)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();

        $this->addFlash("success", "Malheureusement votre annonce ne sera plus présent sur notre site");

        return $this->redirectToRoute('profil');
    }



}
