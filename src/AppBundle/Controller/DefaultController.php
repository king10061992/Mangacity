<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/////////////////////////////////////////////////////////////////////////////////////
///
/// VUE PAR DEFAULT
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */

    //////////////////////////////////////////////////////////////////////////////////////
    ///
    /// Affichage du demmarage
    public function indexAction(Request $request)
    {
        // retourne une par par default
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
