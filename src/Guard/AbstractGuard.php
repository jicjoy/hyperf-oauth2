<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Guard;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Oauth2\GuardInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use  Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HyperfResponseInterface;
use Wolf\Authentication\Oauth2\Api\AuthenticationInterface;
use Wolf\Authentication\Oauth2\Api\UserInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Wolf\Authentication\Oauth2\AuthorizationHandler;
abstract class AbstractGuard implements GuardInterface
{

    /**
     * Undocumented variable
     *
     * @var AuthenticationInterface
     */
    #[Inject(AuthenticationInterface::class)]
    protected AuthenticationInterface $auth;




    #[Inject(HyperfResponseInterface::class)]
    protected HyperfResponseInterface $response;

    #[Inject]
    protected AuthorizationHandler $authorizeHandler;


    #[Inject]
    protected AuthorizationServer $server;


 
    public function __construct(protected string $name,protected ContainerInterface $container,protected RequestHandlerInterface $provider)
    {
    }

     

    public function validateToken(\Psr\Http\Message\ServerRequestInterface $request): ?UserInterface {

        return  $this->auth->authenticate($request);
    }

    public function refreshToken(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface
    {
        return $this->provider->handle($request);
    }

    public function revokeToken(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface{

        $user = $this->auth->authenticate($request);
        if(!$user || empty($user->getDetail('oauth_access_token_id'))) {
                return $this->response->withStatus(401)->json(['message' => 'invalid token','code' => 40101,'data'=> []]);    

        }
        $accessTokenRepository = $this->container->get(AccessTokenRepositoryInterface::class);
        $accessTokenRepository->revokeAccessToken($user->getDetail('oauth_access_token_id'));
         return $this->response->withStatus(200)->json( ['status' => $accessTokenRepository->isAccessTokenRevoked($user->getDetail('oauth_access_token_id'))]);
    }

    public function unauthorizedResponse(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface
    {
       
        return $this->auth->unauthorizedResponse($request);
    }
    
    public function validateAuthorizationRequest(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface
    {
        return $this->authorizeHandler->handle($request);
    }

    public function createResponse($code,$message = 'success', $data = []): ResponseInterface
    {
        $accept = $this->response->getHeaderLine('Accept');
        if (strpos($accept, 'application/json') !== false) {
            return $this->response->withStatus($code)->json(['message' => $message,'code' => $code,'data'=> $data]);
        }
        return $this->response->withStatus($code)->raw(['message' => $message,'code' => $code,'data'=> $data]);
    }
}
