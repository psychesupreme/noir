<?php

namespace App\Services\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Arr;

class AppleProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['name', 'email'];

    /**
     * The delimiter used separating scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://appleid.apple.com/auth/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://appleid.apple.com/auth/token';
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        // For Apple Sign-In, the identity data is inside the id_token parameter
        $idToken = $this->request->input('id_token');
        if (empty($idToken)) {
            return [];
        }

        $segments = explode('.', $idToken);
        if (count($segments) < 2) {
            return [];
        }

        $payload = json_decode(base64_decode(strtr($segments[1], '-_', '+/')), true);
        return $payload ?: [];
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        // Apple only sends the user's name on the first authorization request.
        // It is passed as a stringified JSON in the 'user' POST parameter.
        $userPayload = $this->request->input('user');
        $name = null;
        if ($userPayload) {
            $userJson = json_decode($userPayload, true);
            if ($userJson && isset($userJson['name'])) {
                $name = trim(($userJson['name']['firstName'] ?? '') . ' ' . ($userJson['name']['lastName'] ?? ''));
            }
        }

        return (new User)->setRaw($user)->map([
            'id'       => Arr::get($user, 'sub'),
            'nickname' => null,
            'name'     => $name ?: 'Apple User',
            'email'    => Arr::get($user, 'email'),
            'avatar'   => null,
        ]);
    }
}
