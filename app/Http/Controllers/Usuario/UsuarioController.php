<?php

namespace App\Http\Controllers\Usuario;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:usuario');
    }

    public function index()
    {
        $usuarios = User::with('rol')->get();
        
        return $this->showAll($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'rol_id' => 'required'
        ]);

        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'rol_id' => $request->rol_id,
            'password' => bcrypt($request->password),
        ]);


        $user->save();
        
        return $this->showOne($user);
    }

    /**
     */
    public function show(User $usuario)
    {
        return $this->showOne($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $reglas = [
            'email' => 'required|string|unique:users,email,' . $usuario->id,
        ];

        $this->validate($request, $reglas);

        $usuario->email = $request->email;
        $usuario->rol_id = $request->rol_id;
        $usuario->name = $request->name;

        if (!$usuario->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $usuario->save();
        return $this->showOne($usuario);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $usuario->delete();
        return $this->showOne($usuario,201);
    }

    public function changePassword(Request $request)
    {
        $user = User::find($request->user_id);

        if (Hash::check($request->new_password, $user->password)) {
            return $this->errorResponse('la contraseña actual no puede ser igual a la nueva contraseña',422);
        }

        if (Hash::check($request->old_password, $user->password)) { 
            $user->password = bcrypt($request->new_password);
            $user->save();
        } else {
            return $this->errorResponse('la contraseña anterior es incorrecta',422);
        }


        return $this->showOne($user,'201','update');
    }
}
