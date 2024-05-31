<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\DecretoIsClosed;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app.index');
})->name('home');

Route::get('/dashboard', function () {
    return view('app.index');
})->middleware(['auth', 'verified'])->name('dashboard');

//rubricas
Route::middleware('auth')->group(function () {
    Route::get('/rubricas/{exercicio?}', [\App\Http\Controllers\RubricaController::class, 'index'])->name('rubricas');
    Route::post('/rubrica/{exercicio}/store', [\App\Http\Controllers\RubricaController::class, 'store'])->name('rubrica.store');
    Route::get('/rubrica/{id}/delete', [\App\Http\Controllers\RubricaController::class, 'destroy'])->name('rubrica.delete');
    Route::get('/rubrica/{id}/edit', [\App\Http\Controllers\RubricaController::class, 'edit'])->name('rubrica.edit');
    Route::post('/rubrica/update', [\App\Http\Controllers\RubricaController::class, 'update'])->name('rubrica.update');
});

//leis
Route::middleware('auth')->group(function () {
    Route::get('/leis', [\App\Http\Controllers\LeiController::class, 'index'])->name('leis');
    Route::post('/lei/store', [\App\Http\Controllers\LeiController::class, 'store'])->name('lei.store');
    Route::get('/lei/{id}/show', [\App\Http\Controllers\LeiController::class, 'show'])->name('lei.show');
    Route::get('/lei/{id}/edit', [\App\Http\Controllers\LeiController::class, 'edit'])->name('lei.edit');
    Route::post('/lei/update', [\App\Http\Controllers\LeiController::class, 'update'])->name('lei.update');
    Route::get('/lei/{id}/delete', [\App\Http\Controllers\LeiController::class, 'delete'])->name('lei.delete');
    Route::get('/lei/{id}/destroy', [\App\Http\Controllers\LeiController::class, 'destroy'])->name('lei.destroy');
});

//decretos
Route::middleware('auth')->group(function () {
    Route::get('/decretos/{exercicio?}', [\App\Http\Controllers\DecretoController::class, 'index'])->name('decretos');
    Route::post('/decreto/store', [\App\Http\Controllers\DecretoController::class, 'store'])->name('decreto.store');
    Route::get('/decreto/{id}/show', [\App\Http\Controllers\DecretoController::class, 'show'])->name('decreto.show');
    Route::get('/decreto/{id}/edit', [\App\Http\Controllers\DecretoController::class, 'edit'])->name('decreto.edit')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/update', [\App\Http\Controllers\DecretoController::class, 'update'])->name('decreto.update')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{id}/delete', [\App\Http\Controllers\DecretoController::class, 'delete'])->name('decreto.delete')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{id}/destroy', [\App\Http\Controllers\DecretoController::class, 'destroy'])->name('decreto.destroy')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{id}/verify', [\App\Http\Controllers\DecretoController::class, 'verify'])->name('decreto.verify')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{id}/close', [\App\Http\Controllers\DecretoController::class, 'close'])->name('decreto.close');
    Route::get('/decreto/{id}/open', [\App\Http\Controllers\DecretoController::class, 'open'])->name('decreto.open');
    Route::get('/decreto/{id}/docx', [\App\Http\Controllers\DecretoController::class, 'docx'])->name('decreto.docx');
});

//creditos
Route::middleware('auth')->group(function () {
    Route::get('/decreto/{decreto_id}/creditos', [\App\Http\Controllers\CreditoController::class, 'index'])->name('decreto.creditos');
    Route::post('/decreto/{decreto_id}/credito/store', [\App\Http\Controllers\CreditoController::class, 'store'])->name('decreto.credito.store')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/credito/{id}/delete', [\App\Http\Controllers\CreditoController::class, 'destroy'])->name('decreto.credito.delete')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/creditos/rubricas/update', [\App\Http\Controllers\CreditoController::class, 'updateRubricas'])->name('decreto.creditos.rubricas.update');
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vincular/reducao', [\App\Http\Controllers\VinculoController::class, 'reducao'])->name('decreto.credito.vincular.reducao')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/reducao/{reducao_id}/store', [\App\Http\Controllers\VinculoController::class, 'storeReducao'])->name('decreto.credito.vincular.reducao.store')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vinculo/{id}/delete', [\App\Http\Controllers\VinculoController::class, 'destroy'])->name('decreto.credito.vinculo.delete')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vincular/excesso', [\App\Http\Controllers\VinculoController::class, 'excesso'])->name('decreto.credito.vincular.excesso')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/excesso/{excesso_id}/store', [\App\Http\Controllers\VinculoController::class, 'storeExcesso'])->name('decreto.credito.vincular.excesso.store')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vincular/superavit', [\App\Http\Controllers\VinculoController::class, 'superavit'])->name('decreto.credito.vincular.superavit')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/superavit/{superavit_id}/store', [\App\Http\Controllers\VinculoController::class, 'storeSuperavit'])->name('decreto.credito.vincular.superavit.store')->middleware(DecretoIsClosed::class);
});
//vinculações
Route::middleware('auth')->group(function () {
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vinculo/{id}/delete', [\App\Http\Controllers\VinculoController::class, 'destroy'])->name('decreto.credito.vinculo.delete')->middleware(DecretoIsClosed::class);

    //redução
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vincular/reducao', [\App\Http\Controllers\VinculoController::class, 'reducao'])->name('decreto.credito.vincular.reducao')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/reducao/{reducao_id}/store', [\App\Http\Controllers\VinculoController::class, 'storeReducao'])->name('decreto.credito.vincular.reducao.store')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/reducao/{reducao_id}/confirm', [\App\Http\Controllers\VinculoController::class, 'confirmReducao'])->name('decreto.credito.vincular.reducao.confirm')->middleware(DecretoIsClosed::class);

    //excesso
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vincular/excesso', [\App\Http\Controllers\VinculoController::class, 'excesso'])->name('decreto.credito.vincular.excesso')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/excesso/{excesso_id}/store', [\App\Http\Controllers\VinculoController::class, 'storeExcesso'])->name('decreto.credito.vincular.excesso.store')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/excesso/{excesso_id}/confirm', [\App\Http\Controllers\VinculoController::class, 'confirmExcesso'])->name('decreto.credito.vincular.excesso.confirm')->middleware(DecretoIsClosed::class);

    //superávit
    Route::get('/decreto/{decreto_id}/credito/{credito_id}/vincular/superavit', [\App\Http\Controllers\VinculoController::class, 'superavit'])->name('decreto.credito.vincular.superavit')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/superavit/{superavit_id}/store', [\App\Http\Controllers\VinculoController::class, 'storeSuperavit'])->name('decreto.credito.vincular.superavit.store')->middleware(DecretoIsClosed::class);
    Route::post('/decreto/{decreto_id}/credito/{credito_id}/vincular/superavit/{superavit_id}/confirm', [\App\Http\Controllers\VinculoController::class, 'confirmSuperavit'])->name('decreto.credito.vincular.superavit.confirm')->middleware(DecretoIsClosed::class);

});

//reduções
Route::middleware('auth')->group(function () {
    Route::get('/decreto/{decreto_id}/reducoes', [\App\Http\Controllers\ReducaoController::class, 'index'])->name('decreto.reducoes');
    Route::post('/decreto/{decreto_id}/reducao/store', [\App\Http\Controllers\ReducaoController::class, 'store'])->name('decreto.reducao.store')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/reducao/{id}/delete', [\App\Http\Controllers\ReducaoController::class, 'destroy'])->name('decreto.reducao.delete')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/reducoes/rubricas/update', [\App\Http\Controllers\ReducaoController::class, 'updateRubricas'])->name('decreto.reducoes.rubricas.update');
});

//excesso
Route::middleware('auth')->group(function () {
    Route::get('/decreto/{decreto_id}/excessos', [\App\Http\Controllers\ExcessoController::class, 'index'])->name('decreto.excessos');
    Route::post('/decreto/{decreto_id}/excesso/store', [\App\Http\Controllers\ExcessoController::class, 'store'])->name('decreto.excesso.store')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/excesso/{id}/delete', [\App\Http\Controllers\ExcessoController::class, 'destroy'])->name('decreto.excesso.delete')->middleware(DecretoIsClosed::class);
});

//superávits
Route::middleware('auth')->group(function () {
    Route::get('/decreto/{decreto_id}/superavits', [\App\Http\Controllers\SuperavitController::class, 'index'])->name('decreto.superavits');
    Route::post('/decreto/{decreto_id}/superavit/store', [\App\Http\Controllers\SuperavitController::class, 'store'])->name('decreto.superavit.store')->middleware(DecretoIsClosed::class);
    Route::get('/decreto/{decreto_id}/superavit/{id}/delete', [\App\Http\Controllers\SuperavitController::class, 'destroy'])->name('decreto.superavit.delete')->middleware(DecretoIsClosed::class);
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
