<?php

namespace SauvabelinBundle\ApiController;

use SauvabelinBundle\Entity\BSUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GalerieAPIController
 * @package GalerieBundle\Controller
 */
class ApiAnnuaireController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/annuaire", name="sauvabelin.api.get_annuaire")
     */
    public function getDirectoryAction(Request $request) {

        $query  = $this->get('doctrine.orm.entity_manager')->getRepository('SauvabelinBundle:BSUser')
            ->createQueryBuilder('u');

        $users = $query
            ->where($query->expr()->isNotNull('u.membre'))
            ->join("u.membre", "m")
            ->orderBy('m.nom')
            ->getQuery()
            ->getResult();

        $result = [];

        /** @var BSUser $user */
        foreach($users as $user) {

            $membre         = $user->getMembre();
            $attribution    = $membre->getActiveAttribution();
            $data           = [];
            $data["nom"]    = $membre->getFullName();

            if($membre->getSendableEmail())
                $data['email']      = $membre->getSendableEmail()->getEmail();

            if($membre->getSendableTelephone())
                $data['telephone']  = $membre->getSendableTelephone()->getTelephone();

            if($attribution) {
                $data['groupe']     = $attribution->getGroupe()->getNom();
                $data['fonction']   = $attribution->getFonction()->getNom();
            }

            $result[] = $data;
        }

        return new JsonResponse($this->get('serializer')->serialize($result, 'json'), 200, [], true);
    }
}
