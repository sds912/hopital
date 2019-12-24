<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use App\Repository\SpecialiteRepository;
use App\Utils\MatriculeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medecin")
 */
class MedecinController extends AbstractController
{
    /**
     * @Route("/", name="medecin")
     */
    public function index(MedecinRepository $medecinRepo)
    {
        return $this->render('medecin/index.html.twig', [
          'medecins' => $medecinRepo->findAll()
        ]);
    }

    /**
     * @Route("/new", name="medecin_new")
     */
    public function new(Request $request,MatriculeGenerator $mat_generator, MedecinRepository $medecinRepo)
    {

    
        $medecin = new Medecin();

        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $matricule = $mat_generator->generate($medecin);
            $medecin->setMatricule($matricule);
            $em->persist($medecin);
            $em->flush();
            return $this->redirectToRoute('medecin_new');
        }
        return $this->render('medecin/new.html.twig', [
            'form' => $form->createView(),
            'medecins' => $medecinRepo->findAll(),
        ]);
    }


     /**
     * @Route("/{id}/edit", name="medecin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Medecin $medecin): Response
    {
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medecin_new');
        }

        return $this->render('medecin/edit.html.twig', [
            'medecin' => $medecin,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Returns a JSON string with the specialties of the Service with the providen id.
     * 
     * @param Request $request
     * @return JsonResponse
     * @Route("/service/specialites", name="service_specialite")
     */
    public function SpecialityOfServiceAction(Request $request, SpecialiteRepository $specialiteRepo)
    {
        
        $specialites = $specialiteRepo->createQueryBuilder("s")
            ->andWhere("s.service = :serviceid")
            ->setParameter("serviceid", $request->query->get("serviceid"))
            ->getQuery()
            ->getResult();
        
        
        $responseArray = array();
        foreach($specialites as $specialite){
            $responseArray[] = array(
                "id" => $specialite->getId(),
                "label" => $specialite->getLabel()
            );
        }
        
        return new JsonResponse($responseArray);

       
    }

    /**
     * @Route("/{id}", name="medecin_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Medecin $medecin): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medecin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medecin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('medecin_new');
    }


}
