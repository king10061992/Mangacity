<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    /**
     * @Route(path = "/login",name="login")
     */
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('app_home');
        }
        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');
        return $this->render('@User/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function adminAction(Request $request)
    {

        // Pour récupérer le service UserManager du bundle
        $userManager = $this->get('fos_user.user_manager');

        // Pour récupérer la liste de tous les utilisateurs
        $users = $userManager->findUsers();

        return $this->render('@User/admin.html.twig',array(
            'users'=>$users
        ));
    }

    public function deleteUserAction(Request $request, $id){



        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $user = $em->getRepository('UserBundle:User')->find($id);


        $user->setEnabled(false);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    public function viewUserAction(Request $request, $id){
        // Pour récupérer le service UserManager du bundle
        $userManager = $this->get('fos_user.user_manager');

        // Pour récupérer la liste de tous les utilisateurs
        $user = $userManager->findUserBy(array('id' => $id));

        if (null === $user) {
            throw new NotFoundHttpException("L utilisateur connu sous l id: ".$id." n'existe pas.");
        }

        return $this->render('@User/card.html.twig', array(
            'users'=>$user
        ));
    }
}



