<?php

namespace App\Models;

use App\Models\Scopes\AllowedFilterSearch;
use App\Models\Scopes\AllowedSort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes, AllowedFilterSearch, AllowedSort;

    //protected $table=''; // if model name & table name do not matched
    //protected $primaryKey=''; // if table primary key have different name

    //protected $guarded=[]; // allow to insert mass data using create method only specify column name & if except column data send it showing error
    protected $fillable=['name', 'email', 'address', 'website']; // allow to insert mass data using create method only specify column name & if except column data send it we only save column data and not showing error

    public function contacts(){
        //return $this->hasMany(Contact::class, "company_id");
        return $this->hasMany(Contact::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
