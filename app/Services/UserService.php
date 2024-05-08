<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\DBAL\Connection;
use Modes\Framework\Authentication\AuthUserInterface;
use Modes\Framework\Authentication\UserServiceInterface;
use Modes\Framework\Dbal\EntityService;
use Modes\Framework\Http\Exceptions\NotFoundException;

class UserService implements UserServiceInterface
{
    public function __construct(
        private EntityService $service
    )
    {
    }

    public function store(User $user): User
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $queryBuilder
            ->insert('users')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password' => ':password',
                'created_at' => ':createdAt',
            ])
            ->setParameters([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ])->executeQuery();

        $this->service->save($user);

        return $user;
    }

    public function find(int $id): ?User
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();
        
        $user = $result->fetchAssociative();
        
        if (!$user) {
            return null;
        }
        
        return User::create(
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            createdAt: new \DateTimeImmutable($user['created_at']),
            id: $user['id'],
        );
    }

    public function findOrFail(int $id): User
    {
        $user = $this->find($id);

        if (is_null($user)) {
            throw new NotFoundException('User not found');
        }

        return $user;
    }

    public function getUserByEmail($email): ?AuthUserInterface
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $result = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        $user = $result->fetchAssociative();

        if (!$user) {
            return null;
        }

        return User::create(
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            createdAt: new \DateTimeImmutable($user['created_at']),
            id: $user['id'],
        );

    }
}