<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\{CustomersInfo,CollectionSummary,BillingInfo};
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $results = [];

        // $currentYear = Carbon::now()->year;
        // $previousYear = Carbon::now()->subYear()->year; 
        // $customersAllData = CustomersInfo::with('customerAddress','billing','official','pppUser')->get();
        // $customersData = [
        //     'active' => CustomersInfo::where('status', 'active')->count(),
        //     'pending' => CustomersInfo::where('status', 'pending')->count(),
        //     'free' => CustomersInfo::where('status', 'free')->count(),
        //     'temporary_disable' => CustomersInfo::where('status', 'disable')->count(),
        //     'inactive' => CustomersInfo::where('status', 'inactive')->count(),
        //     'recent' => CustomersInfo::whereMonth('created_at', Carbon::now()->month)
        //                     ->whereYear('created_at', Carbon::now()->year)
        //                     ->count(),
        // ];

        // // $billInformationData =[
        // //     'monthly_rent' => $customersAllData->pluck('billing')->flatten()->sum('monthly_rent'),
        // //     'previous_due' =>-1 * $customersAllData->whereNot('status', 'inactive')->pluck('billing')->flatten()->sum('previous_due'),
        // //     'advance' => $customersAllData->pluck('billing')->flatten()->sum('advance'),
        // //     'due_amount' =>-1 * $customersAllData->whereNot('status', 'inactive')->pluck('billing')->flatten()->sum('due_amount'),
        // //     'paid_amount' => $customersAllData->pluck('billing')->flatten()->sum('paid_amount'),
        // // ];
        // $billInformationData = [
        //     'monthly_rent' => $customersAllData->pluck('billing')->flatten()->sum('monthly_rent'),
        //     'previous_due' => -1 * $customersAllData->reject(function ($customer) {
        //         return $customer->status === 'inactive';
        //     })->pluck('billing')->flatten()->sum('previous_due'),
        //     'advance' => $customersAllData->pluck('billing')->flatten()->sum('advance'),
        //     'paid_amount' => $customersAllData->pluck('billing')->flatten()->sum('paid_amount'),
        //     'due_amount' => -1 * $customersAllData->reject(function ($customer) {
        //         return $customer->status === 'inactive';
        //     })->pluck('billing')->flatten()->sum('due_amount'),
        // ];


        // for ($month = 1; $month <= 12; $month++) {
        //     // Cashflow (আগের বছরের মাসের যোগফল)
        //     $cashflowPreviousYear = CollectionSummary::whereYear('collection_date', $previousYear)
        //         ->whereMonth('collection_date', $month)
        //         ->sum('collection_amount');

        //     // Income (বর্তমান বছরের মাসের যোগফল)
        //     $incomeCurrentYear = CollectionSummary::whereYear('collection_date', $currentYear)
        //         ->whereMonth('collection_date', $month)
        //         ->sum('collection_amount');

        //     // Revenue Difference (বর্তমান ও আগের বছরের মাসের পার্থক্য)
        //     $revenueDifference = $incomeCurrentYear - $cashflowPreviousYear;

        //     // ফলাফল অ্যারেতে সংরক্ষণ করা
        //     $results[$month] = [
        //         'cashflow_previous_year' => $cashflowPreviousYear,
        //         'income_current_year' => $incomeCurrentYear,
        //         'revenue_difference' => $revenueDifference,
        //     ];
        // }

        return view('dashboard');
        // return view('dashboard', compact('results', 'customersData', 'billInformationData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
