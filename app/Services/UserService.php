<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllUsers()
    {
        return $this->userRepo->all();
    }

    public function getUserById($id)
    {
        return $this->userRepo->find($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userRepo->create($data);
    }
    
    public function updateUser($id, array $data)
    {
        if (isset($data['email']) && $this->userRepo->findByEmail($data['email'], $id)) {
            throw new \Exception('Email already exists');
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userRepo->delete($id);
    }

    public function getUserByEmail($email) {
        return $this->userRepo->findByEmail($email);
    }
}
