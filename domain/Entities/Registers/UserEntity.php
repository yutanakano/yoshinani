<?php
namespace Domain\Entities\Registers;

use Domain\ValueObjects\UserValueObject;
use Domain\ValueObjects\NickNameValueObject;
use Domain\ValueObjects\PasswordValueObject;
use Domain\ValueObjects\TimeStampValueObject;
use Illuminate\Contracts\Support\Arrayable;
use stdClass;

/**
 * Class RegisterUserEntity
 * @package Domain\Entities
 */
class UserEntity implements Arrayable
{
    private $user;
    private $nickname;
    private $password;
    private $timeStamp;

    /**
     * RegisterUserEntity constructor.
     * @param stdClass $userRecord
     */
    public function __construct(stdClass $userRecord)
    {
        $this->user      = new UserValueObject($userRecord);
        $this->nickname  = new NickNameValueObject($userRecord);
        $this->password  = new PasswordValueObject($userRecord);
        $this->timeStamp = new TimeStampValueObject();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email'      => $this->getEmail(),
            'name'       => $this->getName(),
            'nickname'   => $this->getNickName(),
            'password'   => $this->getPassword(),
            'active'     => $this->getActive(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->user->getEmail();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->user->getName();
    }

    /**
     * @return string
     */
    public function getNickName(): string
    {
        return $this->nickname->getNickName();
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password->getEncryption();
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->timeStamp->getNow();
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->timeStamp->getNow();
    }
}
