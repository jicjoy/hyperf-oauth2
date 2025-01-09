<?php

namespace Hyperf\Oauth2\Repository;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
 
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Psr\Container\ContainerInterface;
use Hyperf\Oauth2\DBDriverInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

abstract class AbstractRepository
{

    use DBconnectionTrait;


    protected $model;

    protected $table;

    protected $primaryKey = 'id';

    /**
     * 
     * 
     * @var ContainerInterface
     */
    protected $container;

   
    public function __construct(ContainerInterface $container) {

        $this->container     = $container;
    
      
    }

      /**
     * Return a string of scopes, separated by space
     * from ScopeEntityInterface[]
     *
     * @param ScopeEntityInterface[] $scopes
     */
    protected function scopesToString(array $scopes): string
    {
        if (empty($scopes)) {
            return '';
        }

        return trim(array_reduce($scopes, static fn($result, $item): string => $result . ' ' . $item->getIdentifier()));
    }



    protected function revokedToken(string $tokenId,array $where = [],$table = ''): void {
    
        try{
        
            $this->createModelFactory($table)->update($where,
            [
                'revoked' => 1
            ]);
            
   
        }catch(\Exception $e) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
        
    }

    protected function persistNewAuthentication(array $data) {
        try{
      
            $this->createModelFactory()->save($data);
            
   
        }catch(\Exception $e) {
            var_dump($e->getMessage());
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
        
    }

    protected function isTokenRevoked(string $tokenId): bool {

        $entity = $this->createModelFactory()->get($tokenId);
    
        if($entity->getId()) {
            return (bool)$entity->getRevoked();
        }

        return false;
    }

   /**
     * Undocumented function
     *
     * @param array $arguments
     * @return DBDriverInterface
     */
    protected function createModelFactory(?string $table = null): DBDriverInterface {
     
        if($this->table === null) {
            throw new OAuthServerException('the table miss',400,'auth code');
        }
     
        if($this->model === null) $this->model =  new   DBDriver( $this->getDBConnection(),$table ?:  $this->table,$this->primaryKey);
 
        return $this->model;
   }

    
}