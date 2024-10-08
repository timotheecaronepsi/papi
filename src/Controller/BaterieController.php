<?php

namespace App\Controller;

use App\Entity\TypeBatterie;
use App\Form\ModifierTypeBatterieType;
use App\Form\SupprimerTypeBatterieType;
use App\Repository\TypeBatterieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BaterieController extends AbstractController
{
    #[Route('/liste-typebatterie', name: 'app_liste_typebatterie', methods: ['GET', 'POST'])]
    public function listeTypeBaterie(Request $request, TypeBatterieRepository $typeBatterieRepository,): Response
    {
        $typebatterie = $typeBatterieRepository->findAll();
        return $this->render('baterie/liste-typebatterie.html.twig', [
            'typebatterie' => $typebatterie,
        ]);
    }

    #[Route('/modifier-typebatterie/{id}', name: 'app_modifier_typebatterie')]
    public function modifierTypeBaterie(Request $request, TypeBatterie $typeBatterie, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierTypeBatterieType::class, $typeBatterie);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($typeBatterie);
                $em->flush();
                $this->addFlash('notice', 'Type de Batterie modifiée');
                return $this->redirectToRoute('app_liste_typebatterie');
            }
        }
        return $this->render('baterie/modifier-typebatterie.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-typebatterie/{id}', name: 'app_supprimer_typebatterie')]
    public function supprimerTypeBaterie(Request $request, TypeBatterie $typeBatterie, EntityManagerInterface $em): Response {
        if ($typeBatterie != null) {
            $em->remove($typeBatterie);
            $em->flush();
            $this->addFlash('notice', 'Type de batterie supprimée');
        }
        return $this->redirectToRoute('app_liste_typebatterie');
    }

}
