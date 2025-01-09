<?php

namespace Hyperf\Oauth2\Repository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Wolf\Authentication\Oauth2\Entity\ClientEntity;
use Wolf\Authentication\Oauth2\Repository\DB\EntityModelFactory;
class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{

    protected  $table = 'oauth_clients';

    protected  $primaryKey = 'name';
    /**
     * {@inheritDoc}
     */
    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        $clientData = $this->getClientData($clientIdentifier);

        if ($clientData === null || $clientData === []) {
            return null;
        }

        return new ClientEntity(
            $clientIdentifier,
            $clientData['name'] ?? '',
            $clientData['redirect'] ?? '',
            (bool) ($clientData['is_confidential'] ?? null),
            $clientData['user_id']
        );
    }
    /**
     * {@inheritDoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $clientData = $this->getClientData($clientIdentifier);
var_dump($clientData);
        if ($clientData === null || $clientData === []) {
            return false;
        }

 
        if (! $this->isGranted($clientData, $grantType)) {
             
            return false;
        }
    
        if (empty($clientData['secret']) || ! password_verify((string) $clientSecret, $clientData['secret'])) {
            return false;
        }

        return true;
    }

    /**
     * Check the grantType for the client value, stored in $row
     */
    protected function isGranted(array $row, ?string $grantType = null): bool
    {
        return match ($grantType) {
            'authorization_code' => ! ($row['personal_access_client'] || $row['password_client']),
            'personal_access' => (bool) $row['personal_access_client'],
            'password' => (bool) $row['password_client'],
            default => true,
        };
    }

    private function getClientData(string $clientIdentifier): array {

        return $this->createModelFactory()->get($clientIdentifier)->getAttributes();
    }
 
}
