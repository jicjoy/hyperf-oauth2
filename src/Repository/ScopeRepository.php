<?php

namespace Hyperf\Oauth2\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\ScopeEntity;

class ScopeRepository extends AbstractRepository implements ScopeRepositoryInterface
{

    protected $table =  'oauth_scopes';

     /**
     * @param string $identifier
     * @return ScopeEntity|void
     */
    public function getScopeEntityByIdentifier($identifier): ScopeEntityInterface
    {
       
        $entity = $this->createModelFactory()->get($identifier);
       
        $scope = new ScopeEntity();
        if (! $entity->getId()) {
            return $scope;
        }

        $scope->setIdentifier($entity->getId());
        return $scope;
    }

    public function finalizeScopes(array $scopes, string $grantType, ClientEntityInterface $clientEntity, string|null $userIdentifier = null, string|null $authCodeId = null): array {
        var_dump($scopes,$grantType,$clientEntity,$userIdentifier,$authCodeId);
        
        return $scopes;
    }

}
