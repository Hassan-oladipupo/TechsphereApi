<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private \Redis $redis)
    {
    }



    public function createForUser(User $user)
    {
        $accessToken = session_create_id();
        $this->redis->setEx('sessions/' . $accessToken, 3 * 60 * 60, $user->getUserIdentifier());

        return $accessToken;
    }


    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $userId = $this->redis->get('sessions/' . $accessToken);
        if (!$userId) {
            throw new BadCredentialsException('Invalid token');
        }

        return new UserBadge($userId);
    }

    public function clearAccessToken(string $accessToken)
    {
        // Remove the access token from the Redis database
        $this->redis->del('sessions/' . $accessToken);
    }
    public function getAccessTokenForUser(User $user): ?string
    {
        // Retrieve the access token for the given user
        return $this->redis->get('user_tokens/' . $user->getUsername());
    }
}
