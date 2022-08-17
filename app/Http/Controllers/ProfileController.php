<?php

namespace App\Http\Controllers;

//use App\Http\Middleware\Authenticate;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function edit(Profile $profile)
    {
        return view('subscriber.profiles.edit', compact('profile'));
    }

    public function update(ProfileRequest $request, Profile $profile)
    {
        $user = Auth::user();
        if($request->hasFile('photo')){
            //Eliminar foto anterior
            File::delete(public_path('storage/'.$profile->photo));
            //Asignar nueva foto
            $photo = $request['photo']->store('profiles');
        }else{
            $photo = $user->profile->photo;
        }
        //Asignar nombre y correo.
        $user->full_name = $request->full_name;
        $user->email = $request->email;

        //Asignar foto.
        $user->profile->photo = $photo;

        $user->save(); // No se donde está el error.. Está igual que más abajo. (El profe hace "import-class" pero no me figura la opción. Capaz funciona igual)
        
        //Guardar campos de perfil:
        $user->profile->save();

        return redirect()->route('profile.edit', $user->profile->id);
    }
}
