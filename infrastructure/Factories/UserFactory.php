<?php
namespace Infrastructure\Factories;

use Illuminate\Support\Collection;
use stdClass;
use Domain\Entities\UserEntity;
use Domain\Entities\SocialUserEntity;
use Domain\Entities\SocialUserAccountEntity;
use Laravel\Socialite\Contracts\User as SocialUser;

class UserFactory
{
    /**
     * @param stdClass $userRecord
     * @return UserEntity
     */
    public function createUser(stdClass $userRecord): UserEntity
    {
        $userEntity = new UserEntity($userRecord);

        if (property_exists($userRecord, 'id')) {
            $userEntity->setId($userRecord->id);
        }
        if (property_exists($userRecord, 'password')) {
            $userEntity->setPassword($userRecord);
        }

        return $userEntity;
    }

    /**
     * @param UserEntity $userEntity
     * @param string $driverName
     * @param SocialUser $socialUser
     * @return SocialUserEntity
     */
    public function createSocialUser(UserEntity $userEntity, string $driverName, SocialUser $socialUser): SocialUserEntity
    {
        return new SocialUserEntity($userEntity, $driverName, $socialUser);
    }

    /**
     * @param UserEntity $userEntity
     * @param Collection $accountCollection
     * @return SocialUserAccountEntity
     */
    public function createSocialUserAccount(UserEntity $userEntity, Collection $accountCollection): SocialUserAccountEntity
    {
        return new SocialUserAccountEntity($userEntity, $accountCollection);
    }
}
