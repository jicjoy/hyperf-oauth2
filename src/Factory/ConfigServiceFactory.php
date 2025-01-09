<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Factory;

use Psr\Container\ContainerInterface;
use Hyperf\Contract\ConfigInterface;
class ConfigServiceFactory
{


   // 实现一个 __invoke() 方法来完成对象的生产，方法参数会自动注入一个当前的容器实例和一个参数数组
   public function __invoke(ContainerInterface $container, array $parameters = [])
   {
      
     return $container->get(ConfigInterface::class);
       
       
   }
}
