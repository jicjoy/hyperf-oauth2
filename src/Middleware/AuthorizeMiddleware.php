<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Middleware;

use Exception as BaseException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Wolf\Authentication\Oauth2\Api\UserInterface;
use Wolf\Authentication\Oauth2\Entity\UserEntity;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
class AuthorizeMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container ,protected AuthorizationServer $server)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try{
            $authRequest = $this->server->validateAuthorizationRequest($request);
            // OAuth2 UserEntityInterface
            $user = new UserEntity('1');
            var_dump($user);
            $authRequest->setUser($user);
            $authRequest->setAuthorizationApproved(true);

            $request = \Hyperf\Context\Context::set(ServerRequestInterface::class, $request->withAttribute(AuthorizationRequest::class, $authRequest));
          return $handler->handle($request);
        }catch(OAuthServerException $e) {

            var_dump($e->getMessage()); 
        } catch (BaseException $exception) {
             echo 222;
        }
        return $handler->handle($request);
    }
}
