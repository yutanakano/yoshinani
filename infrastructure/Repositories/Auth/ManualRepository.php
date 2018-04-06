<?php
namespace Infrastructure\Repositories\Auth;

use Domain\Entities\UserEntity;
use Domain\Entities\UserDetailEntity;
use Domain\Entities\UserPasswordEntity;
use Infrastructure\Factories\UserFactory;
use Infrastructure\DataSources\Database\Users;
use Infrastructure\DataSources\Database\UsersNickName;
use Infrastructure\DataSources\Database\UsersStatus;
use Infrastructure\DataSources\Database\UsersPassword;
use Infrastructure\Interfaces\Auth\ManualRepositoryInterface;

/**
 * Class ManualRepository
 * @package Infrastructure\Repositories
 */
class ManualRepository implements ManualRepositoryInterface
{
    private $users;
    private $usersStatus;
    private $userNickName;
    private $usersPassword;
    private $userFactory;

    /**
     * ManualRepository constructor.
     * @param Users $users
     * @param UsersStatus $usersStatus
     * @param UsersNickName $userNickName
     * @param UsersPassword $usersPassword
     * @param UserFactory $userFactory
     */
    public function __construct(
        Users $users,
        UsersStatus $usersStatus,
        UsersNickName $userNickName,
        UsersPassword $usersPassword,
        UserFactory  $userFactory
    ) {
        $this->users         = $users;
        $this->usersStatus   = $usersStatus;
        $this->userNickName  = $userNickName;
        $this->usersPassword = $usersPassword;
        $this->userFactory   = $userFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function findUser(string $email): ?UserEntity
    {
        $result = $this->users->findUser($email);
        if (is_null($result)) {
            return null;
        }
        $userRecord = (object) $result;

        return $this->userFactory->createUser($userRecord);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPassword(int $userId): ?UserPasswordEntity
    {
        $result = $this->usersPassword->getUserPassword($userId);
        if (is_null($result)) {
            return null;
        }
        $userPasswordRecord = (object) $result;

        return $this->userFactory->createUserPassword($userId, $userPasswordRecord);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId(array $oldRequest): ?int
    {
        $result = $this->users->getUserId($oldRequest['email']);
        if (is_null($result)) {
            return null;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserDetail(int $userId): ?UserDetailEntity
    {
        $result = $this->users->getUserDetail($userId);
        if (is_null($result)) {
            return null;
        }
        $userDetail = (object) $result;

        return $this->userFactory->createUserDetail($userDetail);
    }

    /**
     * {@inheritdoc}
     */
    public function registerUser(array $oldRequest): int
    {
        $userRecord         = (object) $oldRequest;
        $registerUserEntity = $this->userFactory->createRegisterUser($userRecord);
        $userId             = $this->users->registerUser($registerUserEntity);
        $this->usersStatus->registerActive($userId, $registerUserEntity);
        $registerUserPasswordEntity = $this->userFactory->createRegisterUserPassword($userId, $userRecord);
        $this->usersPassword->registerPassword($userId, $registerUserPasswordEntity);
        $registerUserNickNameEntity = $this->userFactory->createRegisterUserNickName($userId, $userRecord);
        $this->userNickName->registerNickName($userId, $registerUserNickNameEntity);

        return $userId;
    }
}
