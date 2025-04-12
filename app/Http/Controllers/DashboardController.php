<?php

namespace App\Http\Controllers;

use App\Models\{User, Medicine, SalesMedicine, StockInvoice, StockList, SiteSetting, Invoice};

use Illuminate\Http\Request;
// use App\Models\{CustomersInfo,CollectionSummary,BillingInfo};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

        $site_setting = SiteSetting::first();

        // Medicines that are out of stock (quantity < 0)
        $stock_out_medicine = Medicine::where('quantity', '<', 0)->get() ?? null;
        // Medicines that are low in stock based on a setting value
        $low_stock_medicine = $site_setting->medicine_low_stock_quantity ? Medicine::where('quantity', '<', $site_setting->medicine_low_stock_quantity)->get() ?? null : null;
        // Medicines that have expired
        $expired_medicine = StockList::where('expiry_date', '<', Carbon::now()->format('Y-m-d'))->get() ??  null;
        // Medicines that will expire soon (within the set number of days)
        $expire_alert_medicine = $site_setting->medicine_expiry_days ? StockList::where('expiry_date', '<', Carbon::now()->addDays(floatval($site_setting->medicine_expiry_days))->format('Y-m-d'))->get() ?? null :  null;



        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push(Carbon::today()->subDays(6 - $i)->toDateString());
        }

        // Get invoice + sales_medicines data
        $invoiceData = DB::table('invoices')
            ->join('sales_medicines', 'invoices.id', '=', 'sales_medicines.invoice_id')
            ->join('medicines', 'sales_medicines.medicine_id', '=', 'medicines.id')
            ->select(
                DB::raw('DATE(invoices.created_at) as date'),
                'sales_medicines.medicine_id',
                'medicines.name as medicine_name',
                DB::raw('SUM(sales_medicines.initial_quantity) as total_quantity')
            )
            ->whereBetween('invoices.created_at', [Carbon::today()->subDays(6), Carbon::today()->endOfDay()])
            ->groupBy('date', 'sales_medicines.medicine_id', 'medicines.name')
            ->orderBy('date')
            ->get();

        $grouped = $invoiceData->groupBy('medicine_id');

        $series = [];
        foreach ($grouped as $medicine_id => $entries) {
            $name = $entries->first()->medicine_name;
            $dailyQuantities = [];

            foreach ($dates as $date) {
                $entry = $entries->firstWhere('date', $date);
                $dailyQuantities[] = $entry ? (int)$entry->total_quantity : 0;
            }

            $series[] = [
                'name' => $name,
                'data' => $dailyQuantities
            ];
        }


        // Hierarchy tree data
        $users = User::select('id', 'name', 'role', 'manager_id', 'zse_id', 'tse_id', 'profile_photo_path')->get();
        $managers = $users->where('role', 'Manager');
        $treeData = [];

        foreach ($managers as $manager) {
            $zseList = $users->where('manager_id', $manager->id)->where('role', 'Zonal Sales Executive')->values();

            $zseChildren = $zseList->map(function ($zse) use ($users) {
                $tseList = $users->where('zse_id', $zse->id)->where('role', 'Territory Sales Executive')->values();

                $tseChildren = $tseList->map(function ($tse) {
                    return [
                        'id' => 'tse_' . $tse->id,
                        'data' => [
                            'imageURL' => $tse->profile_photo_url,
                            'role' => $tse->role,
                            'name' => $tse->name,
                        ],
                        'options' => [
                            'nodeBGColor' => '#c9cba3',
                            'nodeBGColorHover' => '#c9cba3',
                        ]
                    ];
                });

                return [
                    'id' => 'zse_' . $zse->id,
                    'data' => [
                        'imageURL' => $zse->profile_photo_url,
                        'role' => $zse->role,
                        'name' => $zse->name,
                    ],
                    'options' => [
                        'nodeBGColor' => '#f8ad9d',
                        'nodeBGColorHover' => '#f8ad9d',
                    ],
                    'children' => $tseChildren->toArray()
                ];
            });

            $treeData[] = [
                'id' => 'manager_' . $manager->id,
                'data' => [
                    'imageURL' => $manager->profile_photo_url,
                    'role' => $manager->role,
                    'name' => $manager->name,
                ],
                'options' => [
                    'nodeBGColor' => '#00afb9',
                    'nodeBGColorHover' => '#00afb9',
                ],
                'children' => $zseChildren->toArray()
            ];
        }

        // সর্বোচ্চ parent নোড তৈরি করুন
        $hierarchy = [
            'id' => 'depo',
            'data' => [
                'imageURL' => asset(siteUrlSettings('site_icon')),
                'name' => siteUrlSettings('site_name'),
            ],
            'options' => [
                'nodeBGColor' => '#cdb4db',
                'nodeBGColorHover' => '#cdb4db',
            ],
            'children' => $treeData // একাধিক manager থাকলে সবাই এখানে যুক্ত হবে
        ];
        $hierarchy = !empty($treeData) ? json_encode($treeData[0]) : json_encode([]);
    // send the first tree (or adapt for multiple)


        return view('dashboard', compact('total_medicine', 'total_sales', 'total_purchases', 'total_customers', 'hierarchy', 'stock_out_medicine', 'low_stock_medicine', 'expired_medicine', 'expire_alert_medicine', 'series', 'dates'));
    }
}
