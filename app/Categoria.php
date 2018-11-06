<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //Categorias Modelo
    //protected $table = 'categorias'; Al momento que se crea la clase con el nombre categoria, eloquent asume que la tabla es el nombre de la clase.
    //protected $primarykey = 'id' Eloquent asume que el primaryKey es id
    protected $fillable=['nombre','descripcion','condicion'];

    public function articulos(){
        return $this->hasMany('App\Articulo'); //uno a muchos
    }

}
