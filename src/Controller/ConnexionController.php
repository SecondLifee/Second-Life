<?php
/**
 * Created by PhpStorm.
 * User: legou
 * Date: 14/02/2019
 * Time: 10:25
 */

namespace App\Controller;


use App\Form\ConnecFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController

{
    /**
     * @Route("/connexion", name="connexion_connexion")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
# AuthenticationUtils permet de récupérer la fonction getLastUsername
    public function connexion(AuthenticationUtils $authenticationUtils)
    {
        # Récupération du formulaire
        $form = $this->createForm(ConnecFormType::class, ['email' => $authenticationUtils->getLastUsername()
        ]);
 # On retourne à la vue le formulaire
        return $this->render('connexion/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deconnexion.html",
     *     name="security_deconnexion")
     */
    #Deconnexion se trouve dans security.yaml avec le logout
    public function deconnexion()
    {
    }

}