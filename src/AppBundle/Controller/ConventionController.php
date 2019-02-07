<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Convention;
use AppBundle\Entity\Image;

use AppBundle\Form\ConventionEditType;
use AppBundle\Form\ConventionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ConventionController extends Controller
{


    public function indexAction($page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention')
        ;

        // On récupère l'entité correspondante à l'id $id
        //$convention = $repository->findAll();

        $convention = $repository->findBy(
            array(),
            array('dateStart' => 'desc'),        // Tri
            5,                              // Limite
            0                               // Offset
        );

        $conventions = $this->get('knp_paginator')->paginate($convention, $request->query->get('page', $page),25);

        //Creation du formulaire
        $search= NULL;
        $form = $this->createFormBuilder()
            ->add('search', SearchType::class, array('constraints' => new Length(array('min' => 3)), 'attr' => array('placeholder' => 'Rechercher une convention') ))
            ->add('send', SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

        //Condition de POST + donne les elements pour la recherche
        if ($form->isSubmitted() && $form->handleRequest($request)->isValid()){
            $search = $form['search']->getData();
            return $this->redirectToRoute('app_searchconvention',array('title'=>$search));
        }

        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        // Ici, on récupérera la liste des annonces, puis on la passera au template

        // Mais pour l'instant, on ne fait qu'appeler le template

        return $this->render('@App/Convention/index.html.twig',array(
            'listConvention'=>$conventions,
            'form' => $form->createView(),
            'page'=>$page
        ));
    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function adminAction($page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention')
        ;

        // On récupère l'entité correspondante à l'id $id
        $convention = $repository->findAll();

        $conventions = $this->get('knp_paginator')->paginate($convention, $request->query->get('page', $page),25);

        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        // Ici, on récupérera la liste des annonces, puis on la passera au template

        // Mais pour l'instant, on ne fait qu'appeler le template

        return $this->render('@App/Convention/admin.html.twig',array(
            'listConvention'=>$conventions,
            'page'=>$page
        ));
    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $convention = new Convention();
        $form=$this->get('form.factory')->create(ConventionType::class,$convention);

        // La méthode findAll retourne toutes les catégories de la base de données
        $listConvention = $em->getRepository('AppBundle:Convention')->findAll();



        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($convention);
            $em->flush();

            return $this->redirectToRoute('app_viewconvention',array('id'=>$convention->getId()));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('@App/Convention/add.html.twig', array(
            'form'=>$form->createView()
        ));

    }

    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function editAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $convention = $em->getRepository('AppBundle:Convention')->find($id);

        if (null === $convention) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm( ConventionEditType::class, $convention);

        if ($form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            //return $this->redirect($this->generateUrl('app_viewconvention', array('id' => $convention->getId())));
        }

        return $this->render('@App/Convention/edit.html.twig',array(
            'id' => $id,
            'form'   => $form->createView(),
            'convention' => $convention
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $convention = $em->getRepository('AppBundle:Convention')->find($id);

        if (null === $convention) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->remove($convention);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirectToRoute('app_adminconvention');
        }

        return $this->render('@App/Convention/delete.html.twig', array(
            'id'=>$id,
            'convention' => $convention,
            'form'   => $form->createView(),
        ));
    }

    public function viewAction($id)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention',$id)
        ;

        // On récupère l'entité correspondante à l'id $id
        $convention = $repository->find($id);

        // $partenaire est donc une instance de AppBundle/Partenaire
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $convention) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // Ici, on récupérera l'annonce correspondante à l'id $id
        return $this->render('@App/Convention//view.html.twig', array(
            'convention'=>$convention,
        ));

        // Ici, on récupérera l'annonce correspondante à l'id $id

    }

    public function streetAction($page,$street, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention')
        ;

        $convention = $repository->findBy(
            array('street'=>$street),
            array('dateStart' => 'desc'),        // Tri
            5,                              // Limite
            0                               // Offset
        );

        $conventions = $this->get('knp_paginator')->paginate($convention, $request->query->get('page', $page),25);

        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        return $this->render('@App/Convention/search.html.twig',array(
            'listConvention'=>$conventions,
            'page'=>$page
        ));
    }

    public function localityAction($page,$locality, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention')
        ;

        $convention = $repository->findBy(
            array('locality'=>$locality),
            array('dateStart' => 'desc'),        // Tri
            5,                              // Limite
            0                               // Offset
        );

        $conventions = $this->get('knp_paginator')->paginate($convention, $request->query->get('page', $page),25);

        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        return $this->render('@App/Convention/search.html.twig',array(
            'listConvention'=>$conventions,
            'page'=>$page
        ));
    }

    //Recherche code postal
    public function codeAction($page,$code, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention');

        $convention = $repository->findBy(
            array('code' => $code),
            array('dateStart' => 'desc'),        // Tri
            5,                              // Limite
            0                               // Offset
        );

        $conventions = $this->get('knp_paginator')->paginate($convention, $request->query->get('page', $page),25);

        return $this->render('@App/Convention/search.html.twig', array(
            'listConvention'=>$conventions,
            'page' => $page,
        ));
    }

    public function dateAction($page,$months, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Convention')
        ;

        $months->format(m);

        $convention = $repository->findBy(
            array('dateStart'=>$months),
            array('dateStart' => 'desc'),   // Tri
            5,                              // Limite
            0                               // Offset
        );

        $conventions = $this->get('knp_paginator')->paginate($convention, $request->query->get('page', $page),25);

        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        return $this->render('@App/Convention/search.html.twig',array(
            'listConvention'=>$conventions,
            'page'=>$page
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    public function answerAction($title){

        //Recupere le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire')
        ;

        //Recherche notre resultat et range
        $listPartenaire = $repository->findBy(
            array('title'=>$title),
            array('title' => 'desc'),        // Tri
            5,                              // Limite
            0                               // Offset
        );

        //Retourne le resultat de la recherche
        return $this->render('@App/Partenaire/index.html.twig',array(
            'listPartenaire'=>$listPartenaire
        ));
    }


}
