<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //
    protected $fillable=['idcategoria','codigo','nombre','precio_venta','stock','description','condicion'];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria'); //muchos a uno
    }
}
