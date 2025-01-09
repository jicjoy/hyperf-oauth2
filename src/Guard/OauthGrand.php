<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Guard;
use Wolf\Authentication\Oauth2\Api\UserInterface;
use Wolf\Authentication\Oauth2\Entity\UserEntity;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\Exception\OAuthServerException;
use Exception as BaseException;

class OauthGrand extends AbstractGuard
{


    public function issueToken(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface{

        return $this->provider->handle($request);
    }


    public function authorize(\Psr\Http\Message\ServerRequestInterface $request):  \Psr\Http\Message\ResponseInterface
    {


        try{
            $authRequest = $this->server->validateAuthorizationRequest($request);

            $authRequest->setAuthorizationApproved(false);
            $user = $request->getAttribute(UserInterface::class);
            if($user === null && $authRequest->getClient()->getUserId() === null){
                throw new OAuthServerException('Not support user login',401,'authoreize_type',401);
            }
            // OAuth2 UserEntityInterface
           $user = new UserEntity((string)$authRequest->getClient()->getUserId());
            var_dump($user);
            $authRequest->setUser($user);
            $authRequest->setAuthorizationApproved(true);
           $request = \Hyperf\Context\Context::set(\Psr\Http\Message\ServerRequestInterface::class, $request->withAttribute(AuthorizationRequest::class, $authRequest) );
     
            return $this->authorizeHandler->handle($request);
 

        }catch(OAuthServerException $e) {
            return $e->generateHttpResponse($this->response);
        } catch (BaseException $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
            ->generateHttpResponse($this->response);
        }
        
    
    }
   

}
