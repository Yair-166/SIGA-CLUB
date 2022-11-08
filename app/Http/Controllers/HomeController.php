<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clubes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    public function root()
    {
        return view('index');
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apaterno' => ['required', 'string', 'max:255'],
            'amaterno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar =  $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "User Details Updated successfully!"
            // ], 200); // Status code here
            return redirect()->back();
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "Something went wrong!"
            // ], 200); // Status code here
            return redirect()->back();

        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }

    public function updateClub(Request $request)
    {
        $clubes = new Clubes();

        $clubes->nombre = $request->post('nombre');
        $clubes->idAdministrador = $request->post('idAdministrador');
        $clubes->nomenclatura = $request->post('nomenclatura');
        $clubes->localizacion = $request->post('localizacion');
        $clubes->descripcion = $request->post('descripcion');

        if ($request->file('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = public_path('/images/');
            $foto->move($fotoPath, $fotoName);
            $clubes->foto =  $fotoName;
        }

        $clubes->constanciasExpedidas = 0;

        $clubes->save();

        return redirect()->back()->with('success', 'Club creado correctamente');
    }

    public function editarClub(Request $request)
    {
        //Recibir los datos del formulario y actualizarlos en la base de datos
        $clubes = Clubes::find($request->post('id'));
        
        $clubes->nombre = $request->post('nombre');
        $clubes->idAdministrador = $request->post('idAdministrador');
        $clubes->nomenclatura = $request->post('nomenclatura');
        $clubes->localizacion = $request->post('localizacion');
        $clubes->descripcion = $request->post('descripcion');
        
        if ($request->file('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = public_path('/images/');
            $foto->move($fotoPath, $fotoName);
            $clubes->foto =  $fotoName;
        }
        
        $clubes->update();

        return redirect()->back()->with('success', 'Club actualizado correctamente');
    }

    public function showClubes()
    {
        $clubes = Clubes::all();
        return view('/apps-crm-companies', compact('clubes'));
    }

    public function deleteClub(Request $request)
    {
        $clubes = Clubes::find($request->post('id'));
        $clubes->delete();
        return redirect()->back()->with('success', 'Club eliminado correctamente');
    }
}
