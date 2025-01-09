<?php

use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Wolf\Authentication\Oauth2\TokenEndpointHandler;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Hyperf\Oauth2\Repository;

$dataPath = BASE_PATH . '/data/oauth';

$config = [
    'private_key'          => $dataPath . '/private.key',
    'public_key'           => $dataPath . '/public.key',
    'access_token_expire'  => 'P1D', // 1 day in DateInterval format
    'refresh_token_expire' => 'P1M', // 1 month in DateInterval format
    'auth_code_expire'     => 'PT10M', // 10 minutes in DateInterval format
    // Set value to null to disable a grant
    'grants' => [
        ClientCredentialsGrant::class => ClientCredentialsGrant::class,
        PasswordGrant::class          => PasswordGrant::class,
        AuthCodeGrant::class          => AuthCodeGrant::class,
        RefreshTokenGrant::class      => RefreshTokenGrant::class,
    ],
    'databases'=> 'default',
    'default' => [
        'guard' => 'oauth',
        'provider' =>  'users'
    ],
    "guards" => [
         'oauth' => [
            'driver' => \Hyperf\Oauth2\Guard\OauthGrand::class,
            'provider' => 'users'
         ]
         ],
    'providers' => [
         'users' => [
            'driver' => TokenEndpointHandler::class,
             'providers' => [
                UserRepositoryInterface::class         => \App\Model\User::class,
                AccessTokenRepositoryInterface::class  => Repository\AccessTokenRepository::class,
                AuthCodeRepositoryInterface::class     => Repository\AuthCodeRepository::class,
                ClientRepositoryInterface::class       => Repository\ClientRepository::class,
                RefreshTokenRepositoryInterface::class => Repository\RefreshTokenRepository::class,
                ScopeRepositoryInterface::class        => Repository\ScopeRepository::class,
                
             ]
         ]
    ]
];

// Conditionally include the encryption_key config setting, based on presence of file.
$encryptionKeyFile = $dataPath .  '/encryption.key';

if (is_readable($encryptionKeyFile)) {
    $config['encryption_key'] =  file_get_contents(sprintf("file://%s",$encryptionKeyFile));
}

return $config;