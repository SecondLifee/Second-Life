<?php

namespace App\Controller;
use App\Entity\Membre;

use App\Form\MembreFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $membre = new Membre();
        $membre->setRole(['ROLE_MEMBRE']);


        $form = $this->createForm(MembreFormType::class, $membre)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $membre->setPassword(
                $encoder->encodePassword($membre, $membre->getPassword())
            );


            $em = $this->getDoctrine()->getManager();
            $em->persist($membre);
            $em->flush();


            $this->addFlash('notice',
                'Félicitation, vous pouvez vous connecter !');


        }

        return $this->render('membre/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/modifier-un-membre/{id}",
     *     name="membre-edit")
     * @param UserPasswordEncoderInterface $encoder
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function changeprofil(Request $request, $id, UserPasswordEncoderInterface $encoder)
    {
        # Récupérer du profil en BDD
        $membre = $this->getDoctrine()
            ->getRepository(Membre::class)
            ->find($id);


        $form = $this->createForm(MembreFormType::class, $membre)
            ->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            #Encodage Mot De Passe
            $membre->setPassword(
                $encoder->encodePassword($membre, $membre->getPassword())
            );

            # Sauvegarde en BDD
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('Profil/formmodif.html.twig', [
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/profil", name="profil")
     * @return Response
     */

    public function profil()
    {

        /** @var Membre $membre */
        $membre = $this->getUser();
        $anns = $membre->getAnnonce();

        return $this->render('Profil/profil.html.twig', [
            'anns' => $anns,
            'membre' => $membre
        ]);
    }


}
