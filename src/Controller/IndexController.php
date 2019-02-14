<?php
/**
 * Created by PhpStorm.
 * User: legou
 * Date: 13/02/2019
 * Time: 17:35
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index() {
        return $this->render('index/index.html.twig');
    }

}