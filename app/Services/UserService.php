<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\DBAL\Connection;
use Modes\Framework\Http\Exceptions\NotFoundException;

class UserService
{
    public function __construct(
        private Connection $connection
    )
    {
    }

    public function save(User $user): User
    {
        $queryBuilder = $this->connection->createQueryBuilder();

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

        $id = $this->connection->lastInsertId();

        $user->setId($id);

        return $user;
    }

    public function find(int $id): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();
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
            id: $user['id'],
            createdAt: new \DateTimeImmutable($user['created_at']),
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
}