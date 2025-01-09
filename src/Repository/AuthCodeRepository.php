<?php

namespace Hyperf\Oauth2\Repository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\AuthCodeEntity;
class AuthCodeRepository extends AbstractRepository implements AuthCodeRepositoryInterface
{

    protected $table = 'oauth_auth_codes';

     /**
     * {@inheritDoc}
     */
    public function getNewAuthCode():AuthCodeEntityInterface
    {
        return new AuthCodeEntity();
    }

      /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void {

        $this->persistNewAuthentication([
            'id' => $authCodeEntity->getIdentifier(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => $this->scopesToString($authCodeEntity->getScopes()),
            'revoked' => 0,
            'expires_at' => date(
                'Y-m-d H:i:s',
                $authCodeEntity->getExpiryDateTime()->getTimestamp()
            )
      
        ]);
      
        
    }

    public function revokeAuthCode(string $codeId): void {

        $entity = $this->createModelFactory()->get($codeId);
        if($entity->getId()) {
            $this->createModelFactory()->update(['id' => $entity->getId()],['revoked' => 1]);                   
           //$entity->setAttribute('revoked',1)->update();
            
        }
    }

    public function isAuthCodeRevoked(string $codeId): bool {

        $entity = $this->createModelFactory()->get($codeId);
        if($entity->getId()) {
          
            return (bool)$entity->getAttribute('revoked');
            
        }

        return false;
    }
}
