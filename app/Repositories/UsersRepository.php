<?php

namespace App\Repositories;

use App\Models\Users;

class UsersRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Users();
    }

    public function getAll()
    {
        return $this->model->with(['role.group'])
            ->where('approved_status', 1)
            ->get()
            ->mapWithKeys(function ($user) {
                return [
                    $user->id => $user->first_name . ' ' . $user->last_name . ' (' . $user->role->name . ' - ' . $user->role->group->name . ')'
                ];
            })
            ->toArray();

        // return $this->model->with(['role.group'])
        //     ->where('approved_status', 1)
        //     ->get()
        //     ->map(function ($user) {
        //         return [
        //             'id' => $user->id,
        //             'name' => $user->name . ' (' . $user->role->name . ' - ' . $user->role->group->name . ')'
        //         ];
        //     });
    }
}
