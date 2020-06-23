<?php

namespace App\Providers\Auth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Arr;
use Firebase\JWT\JWT;

class LineProvider extends AbstractProvider implements ProviderInterface
{

    protected $scopeSeparator = ' ';

    protected $scopes = ['profile', 'openid', 'email'];

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://access.line.me/oauth2/v2.1/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://api.line.me/oauth2/v2.1/token';
    }

    /**
     * Override user()
     *
     * @return \Laravel\Socialite\Contracts\User|AbstractProvider|User
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject($this->getUserByToken(
            $token = Arr::get($response, 'id_token')
        ));

        return $user->setToken($token)
            ->setRefreshToken(Arr::get($response, 'refresh_token'))
            ->setExpiresIn(Arr::get($response, 'expires_in'));
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->httpClient->post('https://api.line.me/oauth2/v2.1/verify', [
            'form_params' => [
                'id_token' => $token,
                'client_id' => $this->clientId
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'sub'),
            'nickname' => Arr::get($user, 'name'),
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'avatar' => Arr::get($user, 'picture'),
        ]);
    }

    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);
        $fields['response_type'] = 'code';
        return $fields;
    }

    protected function getTokenFields($code)
    {
        return Arr::add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }
}
