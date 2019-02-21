<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Membre;
use App\Form\AnnonceFormType;
use Doctrine\Common\Persistence\ManagerRegistry;
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
            ->findAll();

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

            return $this->redirectToRoute('profil', [
                'categorie' => $annonce->getCategorie()->getSlug(),
                'slug' => $annonce->getSlug(),
                'id' => $annonce->getId()
            ]);

        }

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


}
