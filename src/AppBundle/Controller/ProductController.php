<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use AppBundle\Entity\Partenaire;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProductController extends Controller
{

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAction(Request $request)
    {

        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Product')
        ;

        $listProduct = $repository->findBy(
            array(),
            array('name' => 'asc'),        // Tri
            100,                              // Limite
            0                               // Offset
        );

        $product = new Product();
        $form=$this->get('form.factory')->create(ProductType::class,$product);

        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_productadmin');
        }

        $product = $repository->findAll();

        return $this->render('@App/Product/admin.html.twig',array(
            'listProduct'=>$listProduct,
            'form'=>$form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (null === $product) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->remove($product);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "La categorie a bien été supprimée.");

            return $this->redirectToRoute('app_productadmin');
        }

        return $this->render('@App/Product/delete.html.twig', array(
            'id'=>$id,
            'product' => $product,
            'form'   => $form->createView(),
        ));

        // Ici, on gérera la suppression de l'annonce en question
        return $this->render('@App/Product/admin.html.twig');
    }
}

