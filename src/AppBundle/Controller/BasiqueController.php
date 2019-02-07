<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BasiqueController extends Controller
{

    /////////////////////////////////////////////////////////////////////////////////////
    ///
    //AFFICHAGE DE LA PAGE D ACCUEIL
    public function homeAction()
    {
        //Retourne au rendu twig de l accueil
        return $this->render('@App/Basique/home.html.twig');

    }


    /////////////////////////////////////////////////////////////////////////////////////
    ///
    //AFFICHAGE DE LA CONTACT
    public function contactAction(Request $request)
    {
        //Creation du message
        $message = new Message();
        $user = $this->getUser();

        //Creation du formulaire
        $form=$this->get('form.factory')->create(MessageType::class,$message);

        // Ajout du detecteur de validation
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            //Sauvegarde du message
            $em = $this->getDoctrine()->getManager();
            $message->setAuthor($user);
            $em->persist($message);
            $em->flush();

            //Redirection vers la page contact
            return $this->redirectToRoute('app_contact');
        }

        //Retour a au formulaire si le message n est pas poster
        return $this->render('@App/Basique/contact.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////
    ///
    //AFFICHAGE DE LA MESSAGERIE INTERNE
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function messageAction($page, Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Message')
        ;

        // On recupere tout les message
        $message = $repository->findAll();

        $messages = $this->get('knp_paginator')->paginate($message, $request->query->get('page', $page),20);
        //mesure de securiter si la page n existe pas ou est mal afficher
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        //Retourne les element que l ont aura besoin
        return $this->render('@App/Basique/message.html.twig',array(
            'listMessage'=>$messages,
            'page'=>$page
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////
    ///
    //AFFICHAGE DE LA PAGE DE SUPPRESSION DES MESSAGES
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request,$id)
    {
        //Recupere la base de donnee
        $em = $this->getDoctrine()->getManager();

        //Selectionne le message
        $message = $em->getRepository('AppBundle:Message')->find($id);

        //Si le message n existe pas affiche un message d erreur
        if (null === $message) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        //Cree le formulaire
        $form = $this->get('form.factory')->create();

        //Regarde si on valide bien la suppression du message
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            //Effacement du message
            $em->remove($message);
            $em->flush();

            //Affiche une confirmation dans la console
            $request->getSession()->getFlashBag()->add('info', "Le message a bien été supprimée.");

            //Redirige vers la route messagerie
            return $this->redirectToRoute('app_messagerie');
        }

        //Retourne les elements important pour notre code
        return $this->render('@App/Basique/delete.html.twig', array(
            'id'=>$id,
            'message' => $message,
            'form'   => $form->createView(),
        ));
    }

    /////////////////////////////////////////////////////////////////////////////////////
    ///
    //AFFICHAGE DU MESSAGE
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewAction($id)
    {
        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Message',$id)
        ;

        // On récupère l'entité correspondante à l'id $id
        $message = $repository->find($id);

        // Ici, on récupérera l'annonce correspondante à l'id $id
        return $this->render('@App/Basique/view.html.twig', array(
            'message'=>$message,
        ));
    }

}
