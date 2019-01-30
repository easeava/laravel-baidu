<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartTP\Authorizer\Auth;

use EaseBaidu\Kernel\AccessToken as BaseAccessToken;
use EaseBaidu\Service\SmartTP\Application;
use Pimple\Container;
use Closure;

/**
 * Class AccessToken
 * 授权APP AccessToken
 *
 * @package EaseBaidu\Service\SmartTP\Authorizer\Auth
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var string
     */
    protected $queryName = 'access_token';

    /**
     * @var string
     */
    protected $endpointToGetToken = '/rest/2.0/oauth/token';

    /**
     * @var string
     */
    protected $cachePrefix = 'easebaidu.smart_tp.app.access_token.';

    /**
     * @var
     */
    protected $refreshTokenCallback;

    /**
     * @var Application
     */
    protected $component;

    public function __construct(Container $app, Application $component)
    {
        parent::__construct($app);
        $this->component = $component;
    }

    public function setRefreshTokenCallback(Closure $closure)
    {
        $this->refreshTokenCallback = $closure;

        return $this;
    }

    /**
     * Credential for get token.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'access_token' => $this->component['access_token']->getToken()['access_token'],
            'refresh_token' => $this->app['config']['refresh_token'],
            'grant_type' => 'app_to_tp_refresh_token',
        ];
    }

    /**
     * @param array $token
     * @param int $lifetime
     * @return $this|BaseAccessToken
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setToken(array $token, int $lifetime = 7200)
    {
        parent::setToken($token);

        if ($this->refreshTokenCallback) {
            call_user_func($this->refreshTokenCallback, $token, $this->app);
        }

        $this->app['config']['refresh_token'] = $token['refresh_token'];

        return $this;
    }
}