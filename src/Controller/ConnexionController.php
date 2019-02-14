<?php
/**
 * Created by PhpStorm.
 * User: legou
 * Date: 14/02/2019
 * Time: 10:25
 */

namespace App\Controller;


use App\Form\ConnecFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion_connexion")
     * @param AuthenticationUtils $authenticationUtils
     */

    public function connexion(AuthenticationUtils $authenticationUtils)
    {
    $form = $this->createForm(ConnecFormType::class, [
'email' =>$authenticationUtils->getLastUsername()
    ]);

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('connexion/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
}