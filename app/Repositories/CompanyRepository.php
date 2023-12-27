<?php

namespace App\Repositories;
use App\Models\Company;
use App\Models\User;

class CompanyRepository
{

    public function pluck(User $user)
    {
        //return Company::orderBy('name')->pluck('name', 'id');
        return Company::forUser($user)->orderBy('name')->pluck('name', 'id');
        // $data = [];
        // $companies = Company::orderBy('name')->get();

        // foreach ($companies as $key => $company) {
        //     $data[$company->id] = $company->name . " (" . $company->contacts->count() . ")";
        // }

        // return $data;
    }
}

?>