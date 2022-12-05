<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clubes;
use App\Models\Inscripciones;
use App\Models\Eventos;
use App\Models\Confi_eventos;
use App\Models\Autoridades;
use App\Models\Archivos;
use App\Models\Evidencias;
use App\Models\Constancias;
use App\Models\Asistencias;
use App\Models\AsistenciasPrevistas;
use App\Mail\Bienvenida;
use App\Mail\AltaUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

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

    //Para correos
    public function emailBienvenida($idalumno, $idclub)
    {
        $alumno = User::find($idalumno);
        $club = Clubes::find($idclub);

        $asunto = 'Bienvenido al ' . $club->nombre;
        $email = $alumno->email;
        $nombre = $alumno->name . " " . $alumno->apaterno . " " . $alumno->amaterno;
        $mensaje = $club->bienvenida;
        $clubname = $club->nombre;

        //Enviar correo de bienvenida
        $data = array(
            'asunto' => $asunto,
            'email' => $email,
            'nombre' => $nombre,
            'mensaje' => $mensaje,
            'clubname' => $clubname,
        );

        //Mail::to($email)->send(new Bienvenida($data));
        Mail::to($email)->send(new Bienvenida($data));
    }

    public function emailAltaUsuario($email, $nombre)
    {
        $asunto = 'Bienvenido a SIGA-Club';
        $mensaje = 'https://panel.sigaclub.com';

        //Enviar correo de bienvenida
        $data = array(
            'asunto' => $asunto,
            'email' => $email,
            'nombre' => $nombre,
            'mensaje' => $mensaje,
        );

        //Mail::to($email)->send(new Bienvenida($data));
        Mail::to($email)->send(new AltaUsuario($data));
    }

    //Para usuarios

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apaterno' => ['required', 'string', 'max:255'],
            'amaterno' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            'descripcion' => ['nullable', 'string', 'max:1024'],
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

    public function editarPassword(Request $request)
    {
        $id = $request->post('id');
        echo $request->post('current_password');
        echo $request->post('password');

        if (!(Hash::check($request->post('current_password'), Auth::user()->password))) {
            return redirect()->back()->with("error","La contraseña actual no coincide.");
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->post('password'));
            echo $user->password;
            $user->update();
            
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back()->with("success","La contraseña ha sido cambiada.");
                response()->json([
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

    public function edituser(request $request)
    {
        $user = new User();

        $user->id = $request->post('id');

        //obtener el usuario por el id
        $user = User::find($user->id);

        $user->name = $request->post('name');
        $user->apaterno = $request->post('apaterno');
        $user->amaterno = $request->post('amaterno');
        $user->email = $request->post('email');
        $user->descripcion = $request->post('descripcion');
        $user->boleta = $request->post('boleta');

        $user->save();

        return redirect()->back();
    }

    public function eliminarUser(request $request)
    {
        $user = Auth::user();
        
        //Verificar si la contraseña es correcta
        if (!(Hash::check($request->post('password_delete'), Auth::user()->password))) {
            return redirect()->back()->with("error","La contraseña actual no coincide.");
        } else {
            //Obtener de la tabla inscripciones todos los registros donde el id_alumno sea igual al id del usuario logueado
            $inscripciones = Inscripciones::where('id_alumno', $user->id)->get();
            //Si es que hay registros en la tabla inscripciones
            if (count($inscripciones) > 0) {
                //Recorrer todos los registros de la tabla inscripciones
                foreach ($inscripciones as $inscripcion) {
                    //Cambiar $inscripcion->active a 0
                    $inscripcion->active = '0';
                    //Guardar los cambios
                    $inscripcion->save();
                }
            }

            $user->email = "";
            $user->password = "";
            $user->active = "0";
            $user->save();
            //Cerrar la sesión del usuario
            Auth::logout();
            return redirect()->back()->with("success","La cuenta ha sido eliminada.");
        }
    }

    public function deleteuser(request $request)
    {
        $user = new User();

        $user->id = $request->post('id');

        //obtener el usuario por el id
        $user = User::find($user->id);

        $user->email = "";
        $user->password = "";
        $user->active = "0";

        $user->save();

        return redirect()->back();
    }

    public function darAdmin($id)
    {
        $user = User::find($id);

        if($user->rol == "administrador")
            $user->rol = "colaborador";
        else
            $user->rol = "administrador";
            
        $user->save();
        return redirect()->back();
    }

    //Para clubes

    public function updateClub(Request $request)
    {
        $clubes = new Clubes();

        $clubes->nombre = $request->post('nombre');
        $clubes->idAdministrador = $request->post('idAdministrador');
        $clubes->nomenclatura = $request->post('nomenclatura');
        $clubes->localizacion = $request->post('localizacion');
        $clubes->descripcion = $request->post('descripcion');
        $clubes->facebook = $request->post('facebook');
        $clubes->bienvenida = $request->post('bienvenida');
        $clubes->active = '1';

        //Checar si existe algún club en la tabla clubes con el mismo nombre
        $club = Clubes::where('nombre', $clubes->nombre)->first();
        //Si existe un club con el mismo nombre
        if($club)
        {
            return redirect()->back()->with("error","Ya existe un club con el mismo nombre.");
        }

        if ($request->file('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = public_path('/images/');
            $foto->move($fotoPath, $fotoName);
            $clubes->foto =  $fotoName;
        }
        else{
            //Retornar mensaje de error
            return redirect()->back()->with("error","No se ha seleccionado una imagen.");
        }

        $clubes->constanciasExpedidas = 0;

        $clubes->save();

        return redirect()->back()->with('des', 'Club creado correctamente');
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
        $clubes->facebook = $request->post('facebook');
        $clubes->bienvenida = $request->post('bienvenida');
        $clubes->active = '1';
        
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
        //Obtener de la tabla eventos todos los registros donde el id_club sea igual al id del club
        $eventos = Eventos::where('id_club', $clubes->id)->count();
        if($eventos > 0){
            $clubes->active = '0';
            $clubes->save();
            return redirect()->back()->with('success', 'Club deshabilitado, porque tiene registros, correctamente');
        }
        else{
            $clubes->delete();
            return redirect()->back()->with('success', 'Club eliminado correctamente');
        }
        
    }

    public function activarClub($id)
    {
        $clubes = Clubes::find($id);
        $clubes->active = '1';
        $clubes->save();
        return redirect()->back()->with('success', 'Club activado correctamente');
    }

    public function asistire($id)
    {
        $id_evento = $id;
        $id_alumno = Auth::user()->id;

        //Ver si el usuario ya está inscrito en el evento en la tabla asistenciasprevistas de la base de datos
        $asistencia = AsistenciasPrevistas::where('id_evento', $id_evento)->where('id_alumno', $id_alumno)->first();
        if($asistencia == null){
            $asistencia = new AsistenciasPrevistas();
            $asistencia->id_evento = $id_evento;
            $asistencia->id_alumno = $id_alumno;
            $asistencia->save();
            return redirect()->back()->with('success', 'Asistencia registrada correctamente');
        }
        else{
            return redirect()->back()->with('error', 'Ya estás pre-inscrito en este evento');
        }
    }

    //Para inscripciones

    public function inscribirse(Request $request){

        $inscripciones = new Inscripciones();

        //Verificar si en la tabla inscripciones exite un registro con el id del usuario y el id del club
        $checkinscripciones = Inscripciones::where('id_alumno', '=', $request->post('id_alumno')) ->where('id_club', '=', $request->post('id_club'))->first();
        if($checkinscripciones){
            $inscripciones = $checkinscripciones;
        }
        else{
            $inscripciones->id_club = $request->post('id_club');
            $inscripciones->id_alumno = $request->post('id_alumno');
        }
            $inscripciones->active = '1';
                
            $inscripciones->save();

        //Obtener el club
        $club = Clubes::find($request->post('id_club'));
        $mensaje = $club->bienvenida;

        //Llamar a la funcion emailBienvenida y enviarle el id_alumno y id_club
        $this->emailBienvenida($request->post('id_alumno'), $request->post('id_club'));


        return redirect()->back()->with('success', $mensaje);
    }

    public function desinscribirse(Request $request){

        $inscripciones = Inscripciones::where('id_alumno', '=', $request->post('id_alumno')) ->where('id_club', '=', $request->post('id_club'))->first();
        $inscripciones->active = '0';
        $inscripciones->save();

        return redirect()->back()->with('des', 'Te has desinscrito correctamente');
    }

    //Para eventos
    public function creaEvento(Request $request)
    {
        $eventos = new Eventos();

        $fecha = $request->post('fecha');
        //Si la longitud de la fecha es 10, es decir, no tiene hora, se le agrega la hora 00:00:00
        if (strlen($fecha) == 10) {
            $eventos->fechaInicio = $fecha;
            $eventos->fechaFin = $fecha;
            $eventos->horaInicio = $request->post('horaInicio');
            $eventos->horaFin = $request->post('horaFin');
        } else {
            //Quitar de la cadena  to 
            $eventos->fechaInicio = substr($fecha, 0, 10);
            $eventos->fechaFin = substr($fecha, 14, 23);
            $eventos->horaInicio = $request->post('horaInicio') . "00:00";
            $eventos->horaFin = $request->post('horaFin') . "23:59";
        }
        
        $eventos->id_club = $request->post('id_club');
        $eventos->nombre = $request->post('title');
        $eventos->tipoAsistencia = $request->post('tipoAsistencia');
        $eventos->tipo = $request->post('category');
        $eventos->modalidad = $request->post('modalidad');
        $eventos->horaInicio = $request->post('horaInicio');
        $eventos->horaFin = $request->post('horaFin');
        $eventos->descripcion = $request->post('descripcion');

        //print_r($eventos->tipo);
        $eventos->save();

        $confi_eventos = new Confi_eventos();
        $confi_eventos->idEvento = $eventos->id;
        $confi_eventos->id_coordinador = "-1";
        $confi_eventos->secondId = NULL;
        $confi_eventos->ultimoQR = NULL;
        $confi_eventos->qrActual = NULL;
        $confi_eventos->isPrivate = 0;
        $confi_eventos->save();

        return redirect()->back()->with('success', 'Evento creado correctamente');
    }

    public function reglasEvento(Request $request)
    {
        $eventos = new Eventos();
        //Encontrar el evento con $request->post('idEvento_reglas')
        $eventos = Eventos::find($request->post('idEvento_reglas'));
        
        $eventos->nombre = $request->post('title');
        $eventos->tipoAsistencia = $request->post('tipoAsistencia');
        $eventos->tipo = $request->post('category');
        $eventos->modalidad = $request->post('modalidad');
        $eventos->horaInicio = $request->post('horaInicio');
        $eventos->horaFin = $request->post('horaFin');
        $eventos->descripcion = $request->post('descripcion');
        $eventos->reglas = $request->post('reglas');
        $eventos->redaccionCoordinador = $request->post('redaccionCoordinador');
        $eventos->redaccionParticipante = $request->post('redaccionParticipante');
        $eventos->update();

        return redirect()->back()->with('success', 'Reglas del evento actualizadas correctamente');
    }

    //Para Confi_eventos
    public function asignarCoordinador(Request $request)
    {
        $confi_eventos = new Confi_eventos();

        $confi_eventos->idEvento = $request->post('idEvento');
        $confi_eventos->secondId = NULL;
        $confi_eventos->id_coordinador = $request->post('id_coordinador');
        $confi_eventos->ultimoQR = "0";
        $confi_eventos->qrActual = "0";
        $confi_eventos->isPrivate = 0;

        $confi_eventos->save();
        
        return redirect()->back()->with('success', 'Coordinador asignado correctamente');
    }

    //Para autoridades
    public function agregarAutoridad(Request $request)
    {
        $autoridades = new Autoridades();

        $autoridades->idClub = $request->post('idClub');
        $autoridades->nombre = $request->post('nombreautoridad');
        $autoridades->aPaterno = $request->post('aPaterno');
        $autoridades->aMaterno = $request->post('aMaterno');
        $autoridades->cargo = $request->post('cargo');

        //print_r($autoridades);

        $autoridades->save();

        return redirect()->back()->with('success', 'Autoridad agregada correctamente');
    }

    public function eliminarAutoridad($id)
    {
        $autoridades = Autoridades::find($id);
        $autoridades->delete();
        return redirect()->back()->with('success', 'Autoridad eliminada correctamente');
    }

    //Para archivos
    public function subirArchivo(Request $request)
    {
        $archivos = new Archivos();

        $club = $request->post('idClub');

        $archivos->idEvento = $request->post('idEvento');
        $archivos->nombreArchivo = $request->post('nombreArchivo');
        $archivos->isPrivate = $request->post('isPrivate');

        if ($request->file('archivo')) {
            $archivo = $request->file('archivo');
            $archivoName =  $archivos->nombreArchivo . '.' . $archivo->getClientOriginalExtension();
            $archivoPath = public_path('/files/'.$club.'/archivos/');
            $archivo->move($archivoPath, $archivoName);
            $archivos->archivo =  $archivoName;
        }

        $archivos->save();

        return redirect()->back()->with('success', 'Archivo subido correctamente');
    }

    public function eliminarArchivo($id)
    {
        $archivos = Archivos::find($id);
        $archivos->delete();
        return redirect()->back()->with('success', 'Archivo eliminado correctamente');
    }

    public function toogleArchivo($id)
    {
        $archivos = Archivos::find($id);
        if ($archivos->isPrivate == 0) {
            $archivos->isPrivate = 1;
        } else {
            $archivos->isPrivate = 0;
        }
        $archivos->save();
        return redirect()->back()->with('success', 'Archivo actualizado correctamente');
    }

    //Para evidencias
    public function subirEvidencia(Request $request)
    {
        $evidencias = new Evidencias();

        $club = $request->post('idClub_ev');

        $evidencias->idEvento = $request->post('idEvento_ev');
        $evidencias->nota = $request->post('nota_ev');

        if ($request->file('archivo_ev')) {
            $evidencia = $request->file('archivo_ev');
            $evidenciaName =  $evidencia->getClientOriginalName();
            $evidenciaPath = public_path('/files/'.$club.'/evidencias/');
            $evidencia->move($evidenciaPath, $evidenciaName);
            $evidencias->archivo =  $evidenciaName;
        }

        $evidencias->save();

        return redirect()->back()->with('success', 'Archivo subido correctamente');
    }

    public function eliminarEvidencia($id)
    {
        $evidencias = Evidencias::find($id);
        $evidencias->delete();

        return redirect()->back()->with('success', 'Autoridad eliminada correctamente');
    }

    //Para constancias
    public function pdf($id, $sel)
    {
        if($sel == 1){
            $pdf = Pdf::setPaper('A4')->loadView('generar-constancia-ipn', compact('id'));
        }else{
            $pdf = Pdf::setPaper('A4', 'landscape')->loadView('generar-constancia-externa', compact('id'));
        }

        return $pdf->download('constancia_'.$id.'.pdf');
    }

    public function pdf2($id, $sel, Request $request)
    {
        if($sel == 1){
            $pdf = Pdf::setPaper('A4')->loadView('generar-constancia-ipn', compact('id', 'request'));
        }else{
            $pdf = Pdf::setPaper('A4', 'landscape')->loadView('generar-constancia-externa', compact('id', 'request'));
        }

        return $pdf->download('constancia_'.$id.'.pdf');
    }

    public function createAcuse(Request $request)
    {
        $id = request('idAsistencia_acuse');
        $asistencia = Asistencias::find($id);

        
        $asistencia->constanciaGenerada = 1;
        $asistencia->save();
        //Obtener el evento con el id de la asistencia de la base de datos
        $evento = Eventos::find($asistencia->idEvento);
        
        //Obtener el club con el id del evento de la base de datos
        $clubes = Clubes::find($evento->id_club);
        //Aumentar en 1 $clubes->constanciasExpedidas
        $clubes->constanciasExpedidas = $clubes->constanciasExpedidas + 1;
        $clubes->save();

        $constancias = new Constancias();
        $constancias->idAsistencia = $id;

        if ($request->file('acuse_file')) {
            $acuse = $request->file('acuse_file');
            $acuseName =  $acuse->getClientOriginalName();
            $acusePath = public_path('/acuses/');
            $acuse->move($acusePath, $acuseName);
            $constancias->acuse =  $acuseName;
        }

        $constancias->fechaExpedicion = date('Y-m-d');
        $constancias->save();
        

        return redirect()->back()->with('success', 'Acuse creado correctamente');
    }

    
}
