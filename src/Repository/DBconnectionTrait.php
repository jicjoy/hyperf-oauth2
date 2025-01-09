<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Repository;
use Hyperf\DbConnection\Db;
trait DBconnectionTrait
{

    protected $db = null;

    public function getDBConnection()
    {
        if($this->db === null) {
            $config = $this->container->get(\Wolf\Authentication\Oauth2\Api\ConfigInterface::class);
     
            $name = $config->get('oauth2.databases', 'default');
           $this->db =  Db::connection($name);
            
        }
      
       return $this->db;
    }
}
