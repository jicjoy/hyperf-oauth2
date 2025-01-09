<?php

namespace Hyperf\Oauth2\Repository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\RefreshTokenEntity;
class RefreshTokenRepository extends AbstractRepository implements RefreshTokenRepositoryInterface
{

    protected $table = 'oauth_refresh_tokens';

    public function getNewRefreshToken(): ?RefreshTokenEntityInterface {

        return new RefreshTokenEntity();
    }

     
    /**
     * 
     * 
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     * @return void
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void {
        
           $this->persistNewAuthentication([
              'id' => $refreshTokenEntity->getIdentifier(),
              'user_id' => $refreshTokenEntity->getAccessToken()->getUserIdentifier(),
              'access_token_id' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
              'revoked'         => 0,
              'expires_at'      =>  date(
                'Y-m-d H:i:s',
                $refreshTokenEntity->getExpiryDateTime()->getTimestamp()
            )
           ]); 
    }

    /**
     * 
     * 
     * @param string $tokenId
     * @return void
     */
    public function revokeRefreshToken(string $tokenId): void {
        $this->revokedToken($tokenId);
    }

    /**
     * 
     * 
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked(string $tokenId): bool {

        return $this->isTokenRevoked($tokenId);
    }

}
