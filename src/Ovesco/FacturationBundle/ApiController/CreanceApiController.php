<?php

namespace Ovesco\FacturationBundle\ApiController;

use Ovesco\FacturationBundle\Entity\Creance;
use Ovesco\FacturationBundle\Form\CreanceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreanceApiController
 * @package Ovesco\FacturationBundle\ApiController
 * @Route("/creance")
 */
class CreanceApiController extends BaseApiController
{
    /**
     * @Route("/test")
     */
    public function test() {
        $facture = $this->get('doctrine.orm.entity_manager')->find('OvescoFacturationBundle:Facture', 1);
        $exporter = $this->get('ovesco.facturation.exporter.pdf_factures');

        return $exporter->export([$facture, $facture]);
    }

    /**
     * @param Request $request
     * @return bool|float|int|string
     * @Route("s", name="ovesco.facturation.api.get_creances", methods={"GET"})
     */
    public function getCreances(Request $request) {

        $em = $this->get('doctrine.orm.default_entity_manager');
        $query = $em->createQueryBuilder()->select('x')
            ->from('OvescoFacturationBundle:Creance', 'x');

        if(($montant = $request->get('montant')) !== null)
            $query->andWhere('x.montant = :montant')->setParameter('montant', $montant);

        if(($titre = $request->get('titre')) !== null)
            $query->andWhere('x.titre LIKE :titre')->setParameter('titre', $titre);


        if(($date = $request->get('date')) !== null)
            $query->andWhere('x.date = :date')->setParameter('date', new \DateTime($date));

        return $this->renderJson($request, $query, ['groups' => ['default', 'with_debiteur', 'creance_with_facture']]);
    }

    /**
     * @param Request $request
     * @Route("", name="ovesco.facturation.api.post_creance", methods={"POST"})
     */
    public function postCreance(Request $request) {

        $creance    = new Creance();
        $form       = $this->createForm(CreanceType::class, $creance);
        $form->handleRequest($request);

    }

    public function putCreance(Request $request) {

    }

    public function deleteCreance(Request $request) {

    }
}