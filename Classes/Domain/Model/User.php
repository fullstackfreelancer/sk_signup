<?php

declare(strict_types=1);

namespace SIMONKOEHLER\Signup\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class User extends AbstractEntity
{
    protected string $username = '';
    protected string $usergroup = '';
    protected string $email = '';
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $password = '';
    protected string $title = '';
    protected string $disable = '';
    protected string $txExtbaseType = '';
    protected string $txSignupKey = '';
    protected string $description = '';
    protected string $address = '';
    protected string $company = '';
    protected string $telephone = '';

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsergroup(): string
    {
        return $this->usergroup;
    }

    public function setUsergroup(string $usergroup): void
    {
        $this->usergroup = $usergroup;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setTxExtbaseType(string $txExtbaseType): void
    {
        $this->txExtbaseType = $txExtbaseType;
    }

    public function setTxSignupKey(string $txSignupKey): void
    {
        $this->txSignupKey = $txSignupKey;
    }

    public function setDisable(string $disable): void
    {
        $this->disable = $disable;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
