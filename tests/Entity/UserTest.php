<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testFirstNameSetterGetter(): void
    {
        $user = new User();
        $firstName = 'Wael';
        $user->setFirstName($firstName);
        $this->assertSame($firstName, $user->getFirstName());
    }

    public function testLastNameSetterGetter(): void
    {
        $user = new User();
        $lastName = 'Maamouri';
        $user->setLastName($lastName);
        $this->assertSame($lastName, $user->getLastName());
    }

    public function testEmailSetterGetter(): void
    {
        $user = new User();
        $email = 'wael@example.com';
        $user->setEmail($email);
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }

    public function testRoles(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles()); // ROLE_USER ajouté automatiquement
    }

    public function testPasswordSetterGetter(): void
    {
        $user = new User();
        $password = 'securepassword';
        $user->setPassword($password);
        $this->assertSame($password, $user->getPassword());
    }

    public function testApiTokenSetterGetter(): void
    {
        $user = new User();
        $token = bin2hex(random_bytes(20));
        $user->setApiToken($token);
        $this->assertSame($token, $user->getApiToken());
    }

    public function testCreatedAtSetterGetter(): void
    {
        $user = new User();
        $createdAt = new \DateTimeImmutable('2026-01-12 12:00:00');
        $user->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $user->getCreatedAt());
    }

    public function testUpdatedAtSetterGetter(): void
    {
        $user = new User();
        $updatedAt = new \DateTimeImmutable('2026-01-12 15:30:00');
        $user->setUpdatedAt($updatedAt);
        $this->assertSame($updatedAt, $user->getUpdatedAt());
    }

    public function testGuestNumberSetterGetter(): void
    {
        $user = new User();
        $guestNumber = 4;
        $user->setGuestNumber($guestNumber);
        $this->assertSame($guestNumber, $user->getGuestNumber());
    }

    public function testAllergySetterGetter(): void
    {
        $user = new User();
        $allergy = 'Peanuts';
        $user->setAllergy($allergy);
        $this->assertSame($allergy, $user->getAllergy());
    }

    public function testSerializationDoesNotExposePassword(): void
    {
        $user = new User();
        $user->setPassword('supersecret');
        $serialized = $user->__serialize();
        $this->assertArrayHasKey("\0".User::class."\0password", $serialized);
        $this->assertNotSame('supersecret', $serialized["\0".User::class."\0password"]);
    }
}
