<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Controller;

use Hyperf\Context\ResponseContext;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Oauth2\AuthManager;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Oauth2\Middleware\AuthorizeMiddleware;
use Psr\Http\Message\StreamInterface;

#[Controller('/oauth')]
class AuthorizeController
{

    #[Inject(AuthManager::class)]
    protected $auth;

    #[PostMapping(path: 'token')]
    public function issueToken(RequestInterface $request)
    {
        return  $this->auth->guard()->issueToken($request);
    }

    #[PostMapping(path: 'refreshToken')]
    public function refreshToken(RequestInterface $request)
    {
        return  $this->auth->guard()->refreshToken($request);
    }

    #[PostMapping(path: 'revokeToken')]
    public function revokeToken(RequestInterface $request) {
        return $this->auth->guard()->revokeToken($request);
    }

    #[RequestMapping(path: 'authorize', methods: ['GET','POST'])]

    public function authorize(RequestInterface $request)
    {
        return $this->auth->guard()->authorize($request);
    }

    #[RequestMapping(path: 'redirect', methods: ['GET','POST'])]
    public function redirect(RequestInterface $request)
    {

        $data = $request->all();
        var_dump($data);
  
    

      $html =  <<<HTML
      <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>  - Authorization</title>

            <!-- Styles -->
            <link rel="stylesheet" href="http://www.dmaku.com/demo/moban/2019090551101975/css/font-awesome.min.css">
            <link rel="stylesheet" href="http://www.dmaku.com/demo/moban/2019090551101975/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="http://www.dmaku.com/demo/moban/2019090551101975/css/demo.css">
            <style type="text/css">
                .form-horizontal {
                    background: #fff;
                    padding-bottom: 40px;
                    border-radius: 15px;
                    text-align: center;
                }

                .form-horizontal .heading {
                    display: block;
                    font-size: 35px;
                    font-weight: 700;
                    padding: 35px 0;
                    border-bottom: 1px solid #f0f0f0;
                    margin-bottom: 30px;
                }

                .form-horizontal .form-group {
                    padding: 0 40px;
                    margin: 0 0 25px 0;
                    position: relative;
                }

                .form-horizontal .form-control {
                    background: #f0f0f0;
                    border: none;
                    border-radius: 20px;
                    box-shadow: none;
                    padding: 0 20px 0 45px;
                    height: 40px;
                    transition: all 0.3s ease 0s;
                }

                .form-horizontal .form-control:focus {
                    background: #e0e0e0;
                    box-shadow: none;
                    outline: 0 none;
                }

                .form-horizontal .form-group i {
                    position: absolute;
                    top: 12px;
                    left: 60px;
                    font-size: 17px;
                    color: #c8c8c8;
                    transition: all 0.5s ease 0s;
                }

                .form-horizontal .form-control:focus + i {
                    color: #00b4ef;
                }

                .form-horizontal .fa-question-circle {
                    display: inline-block;
                    position: absolute;
                    top: 12px;
                    right: 60px;
                    font-size: 20px;
                    color: #808080;
                    transition: all 0.5s ease 0s;
                }

                .form-horizontal .fa-question-circle:hover {
                    color: #000;
                }

                .form-horizontal .main-checkbox {
                    float: left;
                    width: 20px;
                    height: 20px;
                    background: #11a3fc;
                    border-radius: 50%;
                    position: relative;
                    margin: 5px 0 0 5px;
                    border: 1px solid #11a3fc;
                }

                .form-horizontal .main-checkbox label {
                    width: 20px;
                    height: 20px;
                    position: absolute;
                    top: 0;
                    left: 0;
                    cursor: pointer;
                }

                .form-horizontal .main-checkbox label:after {
                    content: "";
                    width: 10px;
                    height: 5px;
                    position: absolute;
                    top: 5px;
                    left: 4px;
                    border: 3px solid #fff;
                    border-top: none;
                    border-right: none;
                    background: transparent;
                    opacity: 0;
                    -webkit-transform: rotate(-45deg);
                    transform: rotate(-45deg);
                }

                .form-horizontal .main-checkbox input[type=checkbox] {
                    visibility: hidden;
                }

                .form-horizontal .main-checkbox input[type=checkbox]:checked + label:after {
                    opacity: 1;
                }

                .form-horizontal .text {
                    float: left;
                    margin-left: 7px;
                    line-height: 20px;
                    padding-top: 5px;
                    text-transform: capitalize;
                }

                .form-horizontal .btn {
                    float: right;
                    font-size: 14px;
                    color: #fff;
                    background: #00b4ef;
                    border-radius: 30px;
                    padding: 10px 25px;
                    border: none;
                    text-transform: capitalize;
                    transition: all 0.5s ease 0s;
                }

                @media only screen and (max-width: 479px) {
                    .form-horizontal .form-group {
                        padding: 0 25px;
                    }

                    .form-horizontal .form-group i {
                        left: 45px;
                    }

                    .form-horizontal .btn {
                        padding: 10px 20px;
                    }
                }
            </style>
            <!--[if IE]>
            <script src="http://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <![endif]-->

            <style>
                .passport-authorize .container {
                    margin-top: 30px;
                }

                .passport-authorize .scopes {
                    margin-top: 20px;
                }

                .passport-authorize .buttons {
                    margin-top: 25px;
                    text-align: center;
                }

                .passport-authorize .btn {
                    width: 125px;
                }

                .passport-authorize .btn-approve {
                    margin-right: 15px;
                }

                .passport-authorize form {
                    display: inline;
                }
            </style>
        </head>
        <body class="passport-authorize">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header">
                            Authorization Request
                        </div>
                        <div class="card-body">
                            <!-- Introduction -->
                            <p><strong></strong> is requesting permission to access your account.</p>

                            <!-- Scope List -->
                      
                            <div class="buttons">
                                <!-- Authorize Button -->
                                <form method="post" action="/oauth/authorize">

                                    <input type="hidden" name="state" value="">
                                    <input type="hidden" name="client_id" value="">
                                    <input type="hidden" name="auth_token" value="">
                                    <button type="submit" class="btn btn-success btn-approve">Authorize</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>

HTML;
       return  ResponseContext::get()->withStatus(200)->html($html);
    }
}
