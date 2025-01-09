<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Hyperf\Oauth2\AuthManager;
class OAuthMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $auth = $this->container->get(AuthManager::class)->guard();
       /**
        * @var 
        * @var \Wolf\Authentication\Oauth2\DefaultUser $user
        */
       $user = $auth->validateToken($request);
 
       if(!$user || empty($user->getDetails())) {
   
           return $auth->unauthorizedResponse($request);
       }
       if($user->getIdentity()) {
          $userEntity = $this->container->get(UserRepositoryInterface::class)->find($user->getIdentity())->first();
 
         $user->setRepository($userEntity);
       }

        $request->withAttribute('user', $user);
        return $handler->handle($request);
    }
}
