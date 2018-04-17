<?php
namespace Infrastructure\Interfaces\Auth;

use Domain\Entities\SocialUserAccountEntity;
use Domain\Entities\Registers\UserEntity as RegisterUserEntity;
use Laravel\Socialite\Contracts\User as SocialUser;

/**
 * Interface SocialRepositoryInterface
 * @package Infrastructure\Interfaces\Auth
 */
interface SocialRepositoryInterface
{
    /**
     * @param SocialUser $socialUser
     * @return int|null
     */
    public function getUserId(SocialUser $socialUser): ?int;

    /**
     * @param SocialUser $socialUser
     * @return RegisterUserEntity
     */
    public function registerUser(SocialUser $socialUser): RegisterUserEntity;

    /**
     * @param int $userId
     * @param string $driverName
     * @param SocialUser $socialUser
     * @return SocialUserAccountEntity|null
     */
    public function findSocialAccount(int $userId, string $driverName, SocialUser $socialUser): ?SocialUserAccountEntity;

    /**
     * @param int $userId
     * @param string $driverName
     * @param SocialUser $socialUser
     */
    public function synchronizeSocialAccount(int $userId, string $driverName, SocialUser $socialUser);
}
