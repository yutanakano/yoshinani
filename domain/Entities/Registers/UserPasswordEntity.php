<?php
namespace Domain\Entities\Registers;

use Domain\ValueObjects\PasswordValueObject;
use Domain\ValueObjects\TimeStampValueObject;
use Illuminate\Contracts\Support\Arrayable;
use stdClass;

/**
 * Class RegisterUserPasswordEntity
 * @package Domain\Entities
 */
class UserPasswordEntity implements Arrayable
{
    private $id;
    private $password;
    private $timeStamp;

    /**
     * RegisterUserPasswordEntity constructor.
     * @param int $userId
     * @param stdClass $userRecord
     */
    public function __construct(
        int $userId,
        stdClass $userRecord
    ) {
        $this->id        = $userId;
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
            'id'         => $this->getId(),
            'password'   => $this->getPassword(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password->getEncryption();
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