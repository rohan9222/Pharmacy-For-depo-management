<?php

namespace App\Http\Controllers;

use App\Models\{User, Medicine, SalesMedicine, StockInvoice, Invoice};

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
        $total_medicine = Medicine::sum('quantity');
        $total_sales = Invoice::sum('grand_total');
        $total_purchases = StockInvoice::sum('total');
        $total_customers = User::where('role', 'customer')->count();

        $users = User::with(['manager', 'salesManager', 'fieldOfficer'])->get();

        // Recursive method to build the hierarchy
        $hierarchy = $this->buildHierarchy($users);
        // \dd($hierarchy);

        return view('dashboard', compact('total_medicine', 'total_sales', 'total_purchases', 'total_customers', 'hierarchy'));
    }

    /**
     * Build the hierarchy of users
     */
    private function buildHierarchy($users, $parentId = null)
    {
        $tree = [];

        foreach ($users as $user) {
            if ($user->manager_id == $parentId) {
                $children = $this->buildHierarchy($users, $user->id);

                $tree[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'imageURL' => $user->profile_photo_url,
                    'children' => $children,
                ];
            }
        }

        return $tree;
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
