<?php

namespace App\Controller;
use App\Entity\Annonce as Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Annonce as Category;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/annonce/add', name: 'users_annonce_add')]
    public function addAnnonces(Request $request, PersistenceManagerRegistry $doctrine)
    {
        $annonce = new Annonce;
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $annonce->setUsers($this->getUser());
            $annonce->setActive(false);
            $em = $doctrine->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/annonce/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
