<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Oauth2;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use Wolf\Authentication\Oauth2\Api\AuthenticationInterface;
use Wolf\Authentication\Oauth2\Grant\AuthCodeGrantFactory;
use Wolf\Authentication\Oauth2\Grant\ClientCredentialsGrantFactory;
use Wolf\Authentication\Oauth2\Grant\PasswordGrantFactory;
use Wolf\Authentication\Oauth2\Grant\RefreshTokenGrantFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Wolf\Authentication\Oauth2\Factory;
use Wolf\Authentication\Oauth2\Response;
use Wolf\Authentication\Oauth2\AuthorizationHandler;
use Wolf\Authentication\Oauth2\TokenEndpointHandler;



class ConfigProvider
{
    public const CONFIG_PATH = 'oauth2';

    /**
     * 提供 OAuth2 的配置信息，包括依赖、命令、发布项和注解扫描路径。
     *
     * @return array 返回一个数组，包含以下键值对：
     *  - dependencies: 获取 OAuth2 的依赖项。
     *  - commands: 获取 OAuth2 的命令。
     *  - publish: 定义需要发布的文件或目录，包括：
     *    - id: 发布项的唯一标识符。
     *    - description: 发布项的描述。
     *    - source: 源文件或目录的路径。
     *    - destination: 目标文件或目录的路径。
     *  - annotations: 配置注解扫描的路径。
     */
    public function __invoke(): array
    {
        return [
            'dependencies' =>  $this->getDependencies(),
            'commands' => $this->getCommands(),
            'publish' => [
                [
                     'id' => 'config',
                 'description' => 'OAuth2 config',
                 'source' => __DIR__ . '/../publish/oauth2.php',  // 对应的配置文件路径
                 'destination' => BASE_PATH . '/config/autoload/oauth2.php', // 复制为这个路径下的该文件
                ],
                [
                 'id' => 'migration',
                 'description' => 'The migration for Oauth2.',
                 'source' => __DIR__ . '/../migrations',
                 'destination' => BASE_PATH . '/migrations',
             ],
             ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }

    /**
     * 获取依赖项数组，用于配置OAuth2服务。
     * 该函数返回一个包含各种接口和其对应工厂类的数组，用于实例化OAuth2服务所需的组件。
     * 可以通过更改别名值来选择不同的适配器。
     * 
     * @return array 依赖项数组
     */
    private function getDependencies(): array
    {
        return [
              
                // Choose a different adapter changing the alias value
                AuthenticationInterface::class         =>  Factory\OAuth2AdapterFactory::class,
            \Wolf\Authentication\Oauth2\Api\ConfigInterface::class => \Hyperf\Oauth2\Factory\ConfigServiceFactory::class,
            ResponseFactoryInterface::class       => Response\ResponseFactory::class,
    

            AuthorizationHandler::class    => Factory\AuthorizationHandlerFactory::class,
            TokenEndpointHandler::class    => Factory\TokenEndpointHandlerFactory::class,
            AuthorizationServer::class     => Factory\AuthorizationServerFactory::class,
            ResourceServer::class          => Factory\ResourceServerFactory::class,

            // Default Grants
            ClientCredentialsGrant::class => ClientCredentialsGrantFactory::class,
            PasswordGrant::class          => PasswordGrantFactory::class,
            AuthCodeGrant::class          => AuthCodeGrantFactory::class,
            RefreshTokenGrant::class      => RefreshTokenGrantFactory::class,

        ];
    }

    /**
     * 获取命令列表。
     *
     * 返回一个数组，包含需要注册到 Hyerf 命令行工具的命令类。
     * 当前仅包含一个命令类 Commands\HyperfOauthKeyCommand，用于处理 OAuth2 密钥相关的操作。
     *
     * @return array 命令类数组
     */
    private function getCommands(): array
    {
        return [
            Commands\HyperfOauthKeyCommand::class
        ];
    }

   
     
}
