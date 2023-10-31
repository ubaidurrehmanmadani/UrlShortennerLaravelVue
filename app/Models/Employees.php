<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employees extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getAllEmployeedWithCompanies($perPage = 10){
        return DB::table('employees')
            ->join('companies', 'employees.company_id', '=', 'companies.id')
            ->select('employees.*', 'companies.id as company_id', 'companies.name as company_name')
            ->orderBy('employees.id')
            ->paginate($perPage);
    }
}
