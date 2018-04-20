<?php
namespace Infrastructure\Factories;

use stdClass;
use Domain\Entities\UserEntity;
use Domain\Entities\SocialUserAccountEntity;

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
     * @param int $userId
     * @param stdClass $socialAccountRecord
     * @return SocialUserAccountEntity
     */
    public function createSocialUserAccount(int $userId, stdClass $socialAccountRecord): SocialUserAccountEntity
    {
        return new SocialUserAccountEntity($userId, $socialAccountRecord);
    }
}
