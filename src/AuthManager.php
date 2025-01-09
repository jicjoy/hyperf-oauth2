<?php

declare(strict_types=1);

namespace Hyperf\Oauth2;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
class AuthManager
{
    protected string $defaultDriver = 'oauth';

    protected array $guards = [];

    protected string $guardName = '';

    protected array $providers = [];

    #[Inject(ConfigInterface::class)]
    protected ConfigInterface $config;

    #[Inject(ContainerInterface::class)]
    protected ContainerInterface $container;


    /**
     * 获取指定名称的认证守卫。
     *
     * @param string|null $name 守卫名称，默认为默认守卫。
     * @return GuardInterface|null 返回指定名称的守卫实例，如果不存在则抛出异常。
     * @throws \Exception 如果不支持指定的驱动名称，则抛出异常。
     */
    public function guard(?string $name = null): ?GuardInterface
    {
        $this->guardName = $name =  $name ??  $this->defaultGuard();
     
     
        $config = $this->config->get(sprintf('guards.%s',$name),[]);
        if (empty($config)) {
            throw new \Exception("Does not support this driver: {$name}");
        }


        $provider = $this->provider($config['provider'] ?$config['provider'] : $this->defaultDriver);
        $container = $this->container;

        return $this->guards[$name] ?? $this->guards[$name] = $this->container->make(
            $config['driver'],
            compact('name', 'container', 'provider')
        );
    }

    /**
     * 获取OAuth2认证管理器的提供者实例。
     *
     * @param string|null $name 提供者名称，如果未指定则使用默认提供者。
     * @return RequestHandlerInterface|null 返回指定提供者的实例，如果不存在则抛出异常。
     * @throws \Wolf\Authentication\Oauth2\Exception\RuntimeException 如果不支持指定的提供者或驱动。
     *
     * 该方法首先确定要使用的提供者名称，然后从配置中获取相应的配置信息。
     * 如果配置中缺少驱动信息，则会抛出异常。
     * 接着，它会初始化提供者，并将它们注册到容器中。
     * 最后，它会初始化用户模型，并返回指定驱动的提供者实例。
     */
    public function provider(?string $name = null): ?RequestHandlerInterface
    {

         $name = $name ?$name:  $this->defaultProvider();
  
        $providersName = sprintf('providers.%s',$name);

        if (empty($this->config->get($providersName))) {
            throw new \Wolf\Authentication\Oauth2\Exception\RuntimeException("Does not support this provider: {$name}");
        }

        $config = $this->config->get($providersName);
       

        //init providers

        if(empty($config['driver'])) {
            throw new \Wolf\Authentication\Oauth2\Exception\RuntimeException("Does not support this driver: {$name}");
        }

         foreach($config['providers'] as $name => $provider) {
            $this->container->define($name,$provider);
        }
       

        return $this->providers[$name] ?? $this->providers[$name] =  $this->container->make($config['driver']);
    }

    /**
     * 获取默认的 OAuth2 提供者名称。
     * 如果配置中指定了默认提供者，则返回该值；否则返回默认驱动名称。
     *
     * @return string 默认的 OAuth2 提供者名称
     */
    public function defaultProvider(): string
    {
        return  $this->config->get('default.provider' ,$this->defaultDriver);
    }

    public function getProvider(string $name): array
    {
        return $this->providers;
    }

    public function getName(): array
    {
        return $this->guards;
    }


    /**
     * 获取默认的认证守卫名称。
     * 如果配置文件中指定了默认守卫，则返回该值；否则返回默认驱动。
     *
     * @return string 默认的认证守卫名称
     */
    public function defaultGuard(): string
    {

        return $this->config->get('default.guard', $this->defaultDriver);
    }

}
