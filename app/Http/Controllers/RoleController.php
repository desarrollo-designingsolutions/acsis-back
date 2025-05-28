<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Resources\Role\MenuCheckBoxResource;
use App\Http\Resources\Role\RoleFormResource;
use App\Http\Resources\Role\RoleListResource;
use App\Models\Role;
use App\Models\User;
use App\Repositories\MenuRepository;
use App\Repositories\RoleRepository;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    use HttpResponseTrait;

    public function __construct(
        protected RoleRepository $roleRepository,
        protected MenuRepository $menuRepository
    ) {}

    public function index(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->roleRepository->filter($request->all());

            $tableData = RoleListResource::collection($data);

            return [
                'code' => 200,
                'tableData' => $tableData,
            ];
        });
    }

    public function create(Request $request)
    {
        return $this->execute(function () use ($request) {
            $menus = $this->menuRepository->list([
                'father_null' => true,
                'withPermissions' => true,
            ], ['children']);

            $menus = MenuCheckBoxResource::collection($menus);

            unset($menus[1]);

            $user = User::find($request->input('user_id'));
            if (! $user || $user->role_id !== Constants::ROLE_SUPERADMIN_UUID) {
                // El usuario no existe o no es superadmin, entonces limitar menús
                unset($menus[5]);
                unset($menus[6]);
                unset($menus[7]);
            }

            return [
                'menus' => $menus,
            ];
        });
    }

    public function edit(Request $request, $id)
    {
        return $this->execute(function () use ($request, $id) {
            $role = $this->roleRepository->find($id);

            $menus = $this->menuRepository->list([
                'typeData' => 'all',
                'father_null' => true,
                'withPermissions' => true,
            ], ['children']);

            $menus = MenuCheckBoxResource::collection($menus);

            unset($menus[1]);

            $user = User::find($request->input('user_id'));
            if (! $user || $user->role_id !== Constants::ROLE_SUPERADMIN_UUID) {
                // El usuario no existe o no es superadmin, entonces limitar menús
                unset($menus[5]);
                unset($menus[6]);
                unset($menus[7]);
            }

            return [
                'code' => 200,
                'role' => new RoleFormResource($role),
                'menus' => $menus,
            ];
        });
    }

    public function store(RoleStoreRequest $request)
    {
        return $this->runTransaction(function () use ($request) {
            $post = $request->except(['permissions']);

            do {
                $nameRole = Str::random(10); // Genera un string aleatorio de 10 caracteres
            } while (Role::where('name', $nameRole)->exists()); // Verifica si ya existe en la base de datos

            $post['name'] = $nameRole;

            $data = $this->roleRepository->store($post);

            $permissions = [
                ...$request['permissions'],
                ...[1],
            ];

            $data->permissions()->sync($permissions);

            $msg = 'agregado';
            if (! empty($request['id'])) {
                $msg = 'modificado';
            }

            return [
                'code' => 200,
                'message' => 'Registro '.$msg.' correctamente',
                'data' => $data,
            ];
        });
    }

    public function destroy($id)
    {
        return $this->runTransaction(function () use ($id) {
            $data = $this->roleRepository->find($id);
            if ($data) {
                $data->delete();
                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            return [
                'code' => 200,
                'message' => $msg,
            ];
        });
    }
}
