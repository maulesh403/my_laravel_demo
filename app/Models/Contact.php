<?php

namespace App\Models;

use App\Models\Scopes\AllowedFilterSearch;
use App\Models\Scopes\AllowedSort;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes, AllowedFilterSearch, AllowedSort;

    protected $fillable=['first_name', 'last_name', 'phone', 'email', 'address', 'company_id'];

    public function Company(){
        // return $this->belongsTo(Company::class, "id");
        return $this->belongsTo(Company::class)->withTrashed();
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    // public function scopeSortByNameAlpha(Builder $query){
    //     return $query->orderBy('first_name');
    // }

    // public function scopeFilterByCompany(Builder $query){
    //     if($companyId = request()->query('company_id')){
    //         $query->where("company_id", $companyId);
    //     }
    //     return $query;
    // }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
