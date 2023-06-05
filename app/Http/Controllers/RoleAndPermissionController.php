<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionController extends Controller
{
    public function upDriver($id){
        $user              = User::find($id);
        $user->removeRole('customer')->revokePermissionTo('order');
        $roles = Role::find(3);
        $user->syncRoles($roles)->syncPermissions('get_customer');
        return response()->json($user->permissions);
    }

    public function upCustomer($id){
        $user              = User::find($id);
        $driver = Role::find(3);
        $user->removeRole($driver)->revokePermissionTo('get_customer');
        $roles = Role::find(2);
        $user->syncRoles($roles)->syncPermissions('order');
        return response()->json($user->permissions);
    }

    public function getdriver(){
        $drivers = User::whereHas(
            'roles', function($q){
                $q->where('name', 'driver');
            }
        )->get();

        return response()->json($drivers);
    }
}
