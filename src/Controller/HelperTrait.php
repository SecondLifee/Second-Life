<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/02/2019
 * Time: 11:58
 */

namespace App\Controller;


use Behat\Transliterator\Transliterator;

trait HelperTrait
{
    /**
     * Permet de générer un Slug à partir d'un String
     * @param $text
     * @return String Slug
     * @see https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     */

    public function slugify($text)
    {
        return Transliterator::transliterate($text);
    }

}