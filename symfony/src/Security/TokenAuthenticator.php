<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $apiToken;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     *
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request): ?string
    {
        return $request->headers->get('X-AUTH-TOKEN');
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if ($this->apiToken !== $credentials) {
            return null;
        }

        return new User();

//        if (null === $credentials) {
//            // The token header was empty, authentication fails with HTTP Status
//            // Code 401 "Unauthorized"
//            return null;
//        }
//
//        // The "username" in this case is the apiToken, see the key `property`
//        // of `your_db_provider` in `security.yaml`.
//        // If this returns a user, checkCredentials() is called next:
//        return $userProvider->loadUserByUsername($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => 'Invalid token'

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication is needed, but it's not sent
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        $data = [
            // you might translate this message
            'message' => 'Invalid token'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
