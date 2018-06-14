<?php
namespace Infrastructure\Repositories;

use Domain\Entities\UserEntity;
use Infrastructure\Factories\UserFactory;
use Infrastructure\DataSources\Database\Users;
use Infrastructure\DataSources\Database\UsersNickName;
use Infrastructure\DataSources\Database\UsersStatus;
use Infrastructure\DataSources\Database\UsersPassword;
use Infrastructure\Interfaces\UserRepositoryInterface;
use Laravel\Socialite\Contracts\User as SocialUser;
use stdClass;

/**
 * Class UserRepository
 * @package Infrastructure\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    private $users;
    private $usersStatus;
    private $usersNickName;
    private $usersPassword;
    private $userFactory;

    /**
     * UserRepository constructor.
     * @param Users $users
     * @param UsersStatus $usersStatus
     * @param UsersNickName $usersNickName
     * @param UsersPassword $usersPassword
     * @param UserFactory $userFactory
     */
    public function __construct(
        Users               $users,
        UsersStatus         $usersStatus,
        UsersNickName       $usersNickName,
        UsersPassword       $usersPassword,
        UserFactory         $userFactory
    ) {
        $this->users               = $users;
        $this->usersStatus         = $usersStatus;
        $this->usersNickName        = $usersNickName;
        $this->usersPassword       = $usersPassword;
        $this->userFactory         = $userFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPassword(int $userId): ?stdClass
    {
        $result = $this->usersPassword->getUserPassword($userId);
        if (is_null($result)) {
            return null;
        }

        return (object) $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(string $email): ?UserEntity
    {
        $result = $this->users->getUser($email);
        if (is_null($result)) {
            return null;
        }
        $userDetail = (object) $result;

        return $this->userFactory->createUser($userDetail);
    }

    /**
     * {@inheritdoc}
     */
    public function registerUser(array $oldRequest): UserEntity
    {
        $userEntity         = $this->userFactory->createUser((object) $oldRequest);
        $userId             = $this->users->registerUser($userEntity);
        $userEntity->setId($userId);
        $userEntity->setPassword((object) $oldRequest);
        $this->usersStatus->registerActive($userEntity);
        $this->usersPassword->registerPassword($userEntity);
        $this->usersNickName->registerNickName($userEntity);

        return $userEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function socialRegisterUser(SocialUser $socialUser): UserEntity
    {
        $userRecord = json_decode(json_encode($socialUser));
        $userEntity = $this->userFactory->createUser($userRecord);
        $userId     = $this->users->registerUser($userEntity);
        $userEntity->setId($userId);
        $this->usersNickName->registerNickName($userEntity);
        $this->usersStatus->registerActive($userEntity);

        return $userEntity;
    }
}
