<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TargetReport;
use App\Models\Team;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */

    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index','show']]);
        // $this->middleware('permission:create-user', ['only' => ['create','store']]);
        // $this->middleware('permission:edit-user', ['only' => ['edit','update']]);
        // $this->middleware('permission:delete-user', ['only' => ['destroy']]);
        $action = request()->route()->getActionMethod(); // Get current method name
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // Permission checks for specific methods
        if (in_array($action, ['index', 'show']) && !$user->canany(['create-user', 'edit-user', 'delete-user'])) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($action, ['create', 'store']) && !$user->can('create-user')) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($action, ['edit', 'update']) && !$user->can('edit-user')) {
            abort(403, 'Unauthorized action.');
        }

        if ($action === 'destroy' && !$user->can('delete-user')) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::latest('id');
        if(!auth()->user()->canany(['edit-manager', 'delete-manager'])){
            $users = $users->whereNotIn('role', ['Super Admin','Manager']);
        }
        if(!auth()->user()->canany(['edit-zonal-sales-executive', 'delete-zonal-sales-executive'])){
            $users = $users->whereNotIn('role', ['Super Admin','Zonal Sales Executive']);
        }
        if(!auth()->user()->canany(['edit-territory-sales-executive', 'delete-territory-sales-executive'])){
            $users = $users->whereNotIn('role', ['Super Admin','Territory Sales Executive']);
        }
        if(!auth()->user()->canany(['edit-depo-manager', 'delete-depo-manager'])){
            $users = $users->whereNotIn('role', ['Super Admin','Depo Incharge']);
        }
        $users = $users->whereNotIn('role', ['Delivery Man','Customer'])->paginate(10);
        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'managers' => User::select('id', 'name')->role('Manager')->get(),
            'salesManagers' => User::select('id', 'name')->role('Zonal Sales Executive')->get(),
            'fieldOfficers' => User::select('id', 'name')->role('Territory Sales Executive')->get(),
            'teams' => Team::select('id', 'name')->get(),
            'roles' => Role::pluck('name')->all(),
            'territories' => User::select('route')->distinct()->pluck('route') ?? [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // Base validation rules
        $validation = [
            'roles.*' => 'string|exists:roles,name',
        ];

        // Check roles from the request
        if (in_array('Zonal Sales Executive', $request->roles) || in_array('Territory Sales Executive', $request->roles)) {
            $validation['manager_id'] = 'required|exists:users,id';
        }

        if (in_array('Territory Sales Executive', $request->roles)) {
            $validation['zse_id'] = 'required|exists:users,id';
        }

        // Validate the request
        $validatedData = $request->validate($validation);

        $input = $request->all();
        $latestInvoiceNo = User::orderByDesc('user_id')->value('user_id');
        $user_id = ($latestInvoiceNo) ? ((int) filter_var($latestInvoiceNo, FILTER_SANITIZE_NUMBER_INT) + 1) : 010500;
        $input['user_id'] = $user_id;
        $input['password'] = Hash::make($request->password);

        if (!empty($request->roles)) {
            // Define valid roles
            $validRoles = ['Super Admin', 'Zonal Sales Executive', 'Territory Sales Executive', 'Delivery Man', 'Manager', 'Depo Incharge'];

            // Filter roles to include only valid ones
            $filteredRoles = array_intersect($validRoles, $request->roles);

            if (!empty($filteredRoles)) {
                // Assign the first valid role from the filtered list
                $input['role'] = reset($filteredRoles);
            }
        }
        $input['route'] = $request->territory;

        $user = User::create($input);
        $user->assignRole($request->roles);

        if (in_array($input['role'], ['Manager', 'Zonal Sales Executive', 'Territory Sales Executive'])) {
            TargetReport::create([
                'user_id' => $user->id,
                'manager' => $user->manager_id ?? null,
                'zse' => $user->zse_id ?? null,
                // 'tse' => $user->tse_id ?? null,
                // 'role' => $input['role'],
                'sales_target' => $user->sales_target ?? 0,
                'target_month' => Carbon::now()->format('F'),
                'target_year' => Carbon::now()->format('Y')
            ]);
        }

        return redirect()->route('users.index')
                ->withSuccess('New user is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        return view('users.edit', [
            'user' => $user,
            'managers' => User::select('id', 'name')->role('Manager')->get(),
            'salesManagers' => User::select('id', 'name')->role('Zonal Sales Executive')->get(),
            'fieldOfficers' => User::select('id', 'name')->role('Territory Sales Executive')->get(),
            'teams' => Team::select('id', 'name')->get(),
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all(),
            'territories' => User::select('route')->distinct()->pluck('route') ?? [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // Base validation rules
        $validation = [
            'roles.*' => 'string|exists:roles,name',
        ];

        // Check roles from the request
        if (in_array('Zonal Sales Executive', $request->roles) || in_array('Territory Sales Executive', $request->roles)) {
            $validation['manager_id'] = 'required|exists:users,id';
        }

        if (in_array('Territory Sales Executive', $request->roles)) {
            $validation['zse_id'] = 'required|exists:users,id';
        }

        // Validate the request
        $validatedData = $request->validate($validation);

        $input = $request->all();
        if(!empty($request->password)){
            $input['password'] = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }
        if (!empty($request->roles)) {
            // Define valid roles
            $validRoles = ['Super Admin', 'Zonal Sales Executive', 'Territory Sales Executive', 'Delivery Man', 'Manager', 'Depo Incharge'];

            // Filter roles to include only valid ones
            $filteredRoles = array_intersect($validRoles, $request->roles);

            if (!empty($filteredRoles)) {
                // Assign the first valid role from the filtered list
                $input['role'] = reset($filteredRoles);
            }
        }
        $input['route'] = $request->territory;
        $user->update($input);

        $user->syncRoles($request->roles);

        return redirect()->back()->withSuccess('User is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // About if user is Super Admin or User ID belongs to Auth User

        if ($user->hasRole('Super Admin') && $user->id == auth()->user()->id)
        {
            // continue;
        } else if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id)
        {
            abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
        }

        $user->syncRoles([]);
        $user->delete();
        return redirect()->route('users.index')->withSuccess('User is deleted successfully.');
    }

    public function salesManagers(Request $request)
    {
        //  Fetch Zonal Sales Executives for the given manager_id
        return User::select('id', 'name')
            ->where('manager_id', $request->manager_id)
            ->role('Zonal Sales Executive') // Assuming a `role` scope or function exists
            ->get();
    }
}
