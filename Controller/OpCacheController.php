<?php

namespace Ibrows\DeployBundle\Controller;

use Ibrows\DeployBundle\Environment\Command\OpCacheResetCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/ibrows/deploy/opcache")
 */
class OpCacheController extends Controller
{
    /**
     * @Route("/reset", name="ibrows_deploy_opcache_reset")
     */
    public function resetAction(Request $request)
    {
        if(!$secret = $request->headers->get('opcachesecret')){
            throw new AccessDeniedException();
        }

        if($secret != $this->get('ibrows_deploy.environment.command.opcachereset')->getSecret()){
            throw new AccessDeniedException();
        }

        opcache_reset();
        return new JsonResponse(array(OpCacheResetCommand::JSON_RESPONSE_KEY => true));
    }
}