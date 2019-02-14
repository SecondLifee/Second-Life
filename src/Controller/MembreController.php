<?php

namespace App\Controller;

use App\Entity\Membre;

use App\Form\MembreFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreController extends AbstractController
{
    /**
     * @Route("/membre.html",
     *     name="membre_inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder)
    {

        # Création d'un Membre
        $membre = new Membre();
        $membre->setRole(['ROLE_MEMBRE']);


        # Création du Formulaire
        $form = $this->createForm(MembreFormType::class, $membre)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            #Encodage Mot De Passe
            $membre->setPassword(
                $encoder->encodePassword($membre, $membre->getPassword())
            );


            # Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();

            # Notification
            $this->addFlash('notice',
                'Félicitation, vous pouvez vous connecter !');

            # Redirection
            #return $this->redirectToRoute('annonce.html.twig');

        }

        return $this->render('membre/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
