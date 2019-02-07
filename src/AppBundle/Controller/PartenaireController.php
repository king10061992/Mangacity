<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Comments;
use AppBundle\Entity\Product;


use AppBundle\Form\CommentsType;
use AppBundle\Form\PartenaireType;
use AppBundle\Form\PartenaireEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PartenaireController extends Controller
{

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    //CREATION DE LA VUE BASIQUE AVEC MOTEUR DE RECHERCHE DE NOMS
    public function indexAction($page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire')
        ;

        //Range l affichage des elements par ordre alphabetique
        $listPartenaire = $repository->findBy(
            array(),
            array('name' => 'asc'),        // Tri
            40,                              // Limite
            0                               // Offset
        );

        $partenaire = $this->get('knp_paginator')->paginate($listPartenaire, $request->query->get('page', $page),4);

        //Creation du formulaire
        $search= NULL;
        $form = $this->createFormBuilder()
            ->add('search', SearchType::class, array('attr' => array('placeholder' => 'Rechercher un partenaire') ))
            ->add('send', SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();


        $form->handleRequest($request);

        //Condition de POST + donne les elements pour la recherche
        if ($form->isSubmitted() && $form->isValid()){
            $search = $form['search']->getData();
            return $this->redirectToRoute('app_searchpartenaire',array('name'=>$search));
        }

        //Regarde si la page existe
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        //Retourne les elements
        return $this->render('@App/Partenaire/index.html.twig',array(
            'listPartenaire'=>$partenaire,
            'form' => $form->createView(),
            'page'=>$page
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    // AFFICHAGE COMMUN DES LOCALITER
    public function localityAction($page,$locality, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire')
        ;

        //Recherche les localiter et range
        $listPartenaire = $repository->findBy(
            array('locality'=>$locality),
            array('name' => 'desc'),        // Tri
            40,                              // Limite
            0                               // Offset
        );

        $partenaire = $this->get('knp_paginator')->paginate($listPartenaire, $request->query->get('page', $page),16);

        //Affiche le contenu dynamique
        return $this->render('@App/Partenaire/search.html.twig',array(
            'listPartenaire'=>$partenaire,
            'page'=>$page
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    // AFFICHAGE COMMUN DES CODE POSTAUX
    public function codeAction($code,$page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire')
        ;

        // Recherche des code postaux et les range
        $listPartenaire = $repository->findBy(
            array('code'=>$code),
            array('name' => 'desc'),        // Tri
            40,                              // Limite
            0                               // Offset
        );

        $partenaire = $this->get('knp_paginator')->paginate($listPartenaire, $request->query->get('page', $page),16);

        //Affiche le contenu dynamique
        return $this->render('@App/Partenaire/search.html.twig',array(
            'listPartenaire'=>$partenaire,
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    //PAGE ADMIN
    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function adminAction($page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire')
        ;

        // On récupère l'entité correspondante à l'id $id
        $partenaire = $repository->findAll();

        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $partenaires = $this->get('knp_paginator')->paginate($partenaire, $request->query->get('page', $page),20);

        // Ici, on récupérera la liste des annonces, puis on la passera au template

        // Mais pour l'instant, on ne fait qu'appeler le template

        return $this->render('@App/Partenaire/admin.html.twig',array(
            'listPartenaire'=>$partenaires,
            'page'=>$page
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    ///COMMENTAIRE
    public function commentsAction($page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Comments')
        ;

        // On récupère l'entité correspondante à l'id $id
        $comments = $repository->findAll();

        $listcomments = $this->get('knp_paginator')->paginate($comments, $request->query->get('page', $page),30);
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        // Ici, on récupérera la liste des annonces, puis on la passera au template

        // Mais pour l'instant, on ne fait qu'appeler le template

        return $this->render('@App/Partenaire/comments.html.twig',array(
            'listComments'=>$listcomments,
            'page'=>$page
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// SUPPRESSION COMMENTAIRE
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteCommentsAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'entité correspondant à l'id $id
        $comment = $em->getRepository('AppBundle:Comments')->find($id);

        // Si l'annonce n'existe pas, on affiche une erreur 404
        if ($comment == null) {
            throw $this->createNotFoundException("L'articles d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST')) {
            // Si la requête est en POST, on deletea l'article

            $em->remove($comment);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');

            // Puis on redirige vers l'accueil
            return $this->redirectToRoute('app_partenaire');
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de delete
        return $this->render('@App/Partenaire/deleteComment.html.twig', array(
            'id'=>$id,
            'formComments'=>$form->createView(),
            'comments' => $comment
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// CREATION DE PARTENAIRE
    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request)
    {

        $partenaire = new Partenaire();
        $form=$this->get('form.factory')->create(PartenaireType::class,$partenaire);

        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($partenaire);
            $em->flush();

            return $this->redirectToRoute('app_viewpartenaire',array('id'=>$partenaire->getId()));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('@App/Partenaire/add.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// MODIFIER UN PARTENAIRE
    /**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function editAction(Request $request,$id)
    {
        // Ici, on récupérera l'annonce correspondante à $id
        // La méthode findAll retourne toutes les catégories de la base de données
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $partenaire = $em->getRepository('AppBundle:Partenaire')->find($id);

        if (null === $partenaire) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm( PartenaireEditType::class, $partenaire);

        if ($form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirect($this->generateUrl('app_viewpartenaire', array('id' => $partenaire->getId())));
        }

        return $this->render('@App/Partenaire/edit.html.twig',array(
            'id' => $id,
            'form'   => $form->createView(),
            'partenaire' => $partenaire
        ));
    }


    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// EFFACER UN PARTENAIRE
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $partenaire = $em->getRepository('AppBundle:Partenaire')->find($id);

        if (null === $partenaire) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $comments=$em->getRepository('AppBundle:Comments')->findOneBy(array('partenaire'=>$id));
            $em->remove($comments);
            $em->remove($partenaire);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirectToRoute('app_adminpartenaire');
        }

        return $this->render('@App/Partenaire/delete.html.twig', array(
            'id'=>$id,
            'partenaire' => $partenaire,
            'form'   => $form->createView(),
        ));

        // Ici, on gérera la suppression de l'annonce en question
        return $this->render('@App/Partenaire/admin.html.twig');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// AFFICHER UN PARTENAIRE
    public function viewAction(Request $request,$id)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire',$id)
        ;

        // On récupère l'entité correspondante à l'id $id
        $partenaire = $repository->find($id);
        $user = $this->getUser();

        $comments= new Comments();
        $comments->getPartenaire($partenaire);

        $form= $this->get('form.factory')->create(CommentsType::class,$comments);


        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $em =$this->getDoctrine()->getManager();
            $comments->setPartenaire($partenaire);
            $comments->setAuthor($user);
            $em->persist($comments);
            $em->flush();

            return $this->redirectToRoute('app_viewpartenaire',array('id'=>$id));
        }

        // $partenaire est donc une instance de AppBundle/Partenaire
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $partenaire) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listComments = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Comments')
            ->findBy(array('partenaire'=>$partenaire))
        ;

        return $this->render('@App/Partenaire/view.html.twig', array(
            'Partenaire'=>$partenaire,
            'formComments'=>$form->createView(),
            'ListComments'=>$listComments
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    ///


    /////////////////////////////////////////////////////////////////////////////////////////////
    ///
    /// AFFICHAGE DE LA REPONSE DE NOTRE RECHERCHE
    public function answerAction($name){

        //Recupere le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Partenaire')
        ;

        $query = $repository->createQueryBuilder('p')
            ->where('p.name LIKE :name')
            ->setParameter('name', '%'.addcslashes($name, '%_').'%')
            ->getQuery();
        $repository = $query->getResult();




        //Retourne le resultat de la recherche
        return $this->render('@App/Partenaire/search.html.twig',array(
            'listPartenaire'=>$repository
        ));
    }

}
