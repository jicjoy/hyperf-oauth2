<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Commands;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
 use Wolf\Authentication\Oauth2\Commands\TraitOauthKeyCommand;
class HyperfOauthKeyCommand extends HyperfCommand
{
    use TraitOauthKeyCommand;
    protected ?string $name ="oauth:key:gen";

        
    public function __construct(ContainerInterface $containerInterface,string|null $name = null) {
      
        parent::__construct($name);
        $this->container = $containerInterface;

    }
}
