<?php
use App\Familia;
use App\Membros;
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){
        #$familias = Familia::all()->where('f_user_creator_id', Auth::user()->id)->sortByDesc('id');
        $familias = new Familia();
        $membros = Membros::all()->where('m_user_id', Auth::user()->id)->sortByDesc('id');
        return view('home', compact('familias','membros'));
    }
    return view('index');
});

Route::get('/addGrupoFamiliar/{limitMax}', function ($limitMax) {
    if(Auth::check()){
        return view('addGrupoFamiliar', compact('limitMax'));
    }
    return view('index');
});

Route::get('/grupoFamiliar/{id}', function ($id){

    if(Auth::check()){
        $familias = new Familia();
        $membros = Membros::all()->where('m_user_id', Auth::user()->id)->sortByDesc('id');
        if(count($membros) >= 1){
            $membro = Membros::all()->where('m_user_id', Auth::user()->id)->sortByDesc('id')->first();
            $familiaSolicitada = $familias::all()->where('id', $id)->first();
            $qtMembros = count(Membros::all()->where('m_familia_id', $familiaSolicitada->id));
            return view('grupoFamiliar', compact('familias','familiaSolicitada','qtMembros'), compact('membros'));
        }
    }
    return redirect('/');
});

Route::get('/editar/grupoFamiliar/{id}', function($id){
    if(Auth::check()){
        $membros = Membros::all()->where('m_familia_id', $id);
        $familia = Familia::find($id);
        $user = new User(); 
        return view('editarGrupoFamiliar', compact('familia','membros','user'));
    }
    return view('index');
});
Route::get('/editar/grupoFamiliar/{idFamilia}/error',function($idFamilia){
    if(Auth::check()){
        $familia = Familia::find($idFamilia); 
        $membros = Membros::all()->where('m_familia_id', $idFamilia);
        $error = true;
        return view('editarGrupoFamiliar', compact('familia','error','membros'));
    }
    return view('index');
});

Route::post('/editar/grupoFamiliar/{idFamilia}','ControllerMembros@store');
Route::get('/grupoFamiliar','ControllerFamilia@index');
Route::get('/addGrupoFamiliar','ControllerFamilia@create');
Route::post('/addGrupoFamiliar/insert','ControllerFamilia@store');
Route::get('/editar/grupoFamiliar/{idFamilia}/excluir/{idMembro}', 'ControllerMembros@destroy');
Auth::routes();


