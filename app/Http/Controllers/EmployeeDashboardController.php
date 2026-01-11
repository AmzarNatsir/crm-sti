<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();

        // Age Summary
        $ageDistribution = [
            '< 25' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 25')->count(),
            '25-34' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 25 AND 34')->count(),
            '35-44' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 35 AND 44')->count(),
            '45-54' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 45 AND 54')->count(),
            '55+' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 55')->count(),
        ];

        // Gender Distribution
        $genderDistribution = Employee::select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get()
            ->pluck('count', 'gender')
            ->toArray();

        // Length of Service (Join Date)
        $serviceLength = [
            '< 1 Year' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, join_date, CURDATE()) < 1')->count(),
            '1-3 Years' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, join_date, CURDATE()) BETWEEN 1 AND 2')->count(),
            '3-5 Years' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, join_date, CURDATE()) BETWEEN 3 AND 4')->count(),
            '5-10 Years' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, join_date, CURDATE()) BETWEEN 5 AND 9')->count(),
            '10+ Years' => Employee::whereRaw('TIMESTAMPDIFF(YEAR, join_date, CURDATE()) >= 10')->count(),
        ];

        return view('employees.dashboard', compact(
            'totalEmployees',
            'ageDistribution',
            'genderDistribution',
            'serviceLength'
        ));
    }
}
