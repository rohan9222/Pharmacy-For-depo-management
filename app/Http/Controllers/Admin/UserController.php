<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('users.index', [
            'users' => User::latest('id')->whereNotIn('role', ['Delivery Man','Customer'])->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'managers' => User::select('id', 'name')->role('Manager')->get(),
            'salesManagers' => User::select('id', 'name')->role('Sales Manager')->get(),
            'fieldOfficers' => User::select('id', 'name')->role('Field Officer')->get(),
            'teams' => Team::select('id', 'name')->get(),
            'roles' => Role::pluck('name')->all()
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
        if (in_array('Sales Manager', $request->roles) || in_array('Field Officer', $request->roles)) {
            $validation['manager_id'] = 'required|exists:users,id';
        }

        if (in_array('Field Officer', $request->roles)) {
            $validation['sales_manager_id'] = 'required|exists:users,id';
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
            $validRoles = ['Super Admin', 'Sales Manager', 'Field Officer', 'Delivery Man', 'Manager', 'Depo Incharge'];

            // Filter roles to include only valid ones
            $filteredRoles = array_intersect($validRoles, $request->roles);

            if (!empty($filteredRoles)) {
                // Assign the first valid role from the filtered list
                $input['role'] = reset($filteredRoles);
            }
        }

        $user = User::create($input);
        $user->assignRole($request->roles);

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
            'salesManagers' => User::select('id', 'name')->role('Sales Manager')->get(),
            'fieldOfficers' => User::select('id', 'name')->role('Field Officer')->get(),
            'teams' => Team::select('id', 'name')->get(),
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all()
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
        if (in_array('Sales Manager', $request->roles) || in_array('Field Officer', $request->roles)) {
            $validation['manager_id'] = 'required|exists:users,id';
        }

        if (in_array('Field Officer', $request->roles)) {
            $validation['sales_manager_id'] = 'required|exists:users,id';
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
            $validRoles = ['Super Admin', 'Sales Manager', 'Field Officer', 'Delivery Man', 'Manager', 'Depo Incharge'];

            // Filter roles to include only valid ones
            $filteredRoles = array_intersect($validRoles, $request->roles);

            if (!empty($filteredRoles)) {
                // Assign the first valid role from the filtered list
                $input['role'] = reset($filteredRoles);
            }
        }

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
        //  Fetch sales managers for the given manager_id
        return User::select('id', 'name')
            ->where('manager_id', $request->manager_id)
            ->role('Sales Manager') // Assuming a `role` scope or function exists
            ->get();
    }
}
