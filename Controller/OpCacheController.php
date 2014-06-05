<?php

namespace Ibrows\DeployBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ibrows/deploy/opcache")
 */
class OpCacheController extends Controller
{
    /**
     * @Route("/reset", name="ibrows_deploy_opcache_reset")
     */
    public function resetAction()
    {
        opcache_reset();
        return new JsonResponse(array('success' => true));
    }
}