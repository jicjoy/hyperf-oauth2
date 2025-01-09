<?php

declare(strict_types=1);

namespace Hyperf\Oauth2;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Wolf\Authentication\Oauth2\Api\UserInterface;
interface GuardInterface
{

    /**
     * Summary of issueToken
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
 
    public function issueToken(ServerRequestInterface $request): ResponseInterface;

    public function validateToken(ServerRequestInterface $request):?UserInterface;

    public function refreshToken(ServerRequestInterface $request): ResponseInterface;

    public function revokeToken(ServerRequestInterface $request): ResponseInterface;

}
