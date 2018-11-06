<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Articulos
                        <button type="button" class="btn btn-secondary" @click="abrirModal('articulo','registrar')" >
                            <i class="icon-plus"></i>  &nbsp; Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="codigo">Código</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary" @click="listarArticulo(1,buscar,criterio)" ><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('articulo','actualizar',articulo)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="articulo.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.nombre_categoria"></td>
                                    <td v-text="articulo.precio_venta"></td>
                                    <td v-text="articulo.stock"></td>
                                    <td v-text="articulo.description"></td>
                                    <td>
                                        <div v-if="articulo.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' :'']">
                                    <a class="page-link" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Categoria</label>
                                    <div class="col-md-9">
                                       <select class="form-control" v-model="idcategoria">
                                           <option value="0" disabled>Seleccione</option>
                                           <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de Articulo">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Codigo</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="codigo" class="form-control" placeholder="Codigo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Precio de Venta</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="precio_venta" class="form-control" placeholder="Precio de Venta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="Stock">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese Descripcion">
                                    </div>
                                </div>
                                <div v-show="errorArticulo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="registrarArticulo()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarArticulo()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar la categoría?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        data(){
            return {
                articulo_id : 0 ,
                idcategoria: 0,
                nombre_categoria:'',
                codigo:'',
                nombre :'',
                precio_venta:0,
                stock:0,
                descripcion:'',
                arrayArticulo:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: '',
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
                pagination :{
                    'total'         :  0,
                    'current_page'  :  0,
                    'per-page'      :  0,
                    'last_page'     :  0,
                    'from'          :  0,
                    'to'            :  0
                },
                offset : 3,
                criterio: 'nombre',
                buscar: '',
                arrayCategoria:[]
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to>= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray= [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods:{
            listarArticulo(page,buscar,criterio){
                let me = this;
                var url='/articulo?page='+page+'&buscar='+buscar+'&criterio='+criterio;

                axios.get(url).then(function (response){
                    var respuesta = response.data
                    me.arrayArticulo = respuesta.articulos.data;
                    me.pagination = respuesta.pagination;
                }).catch(function (error){
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;

                me.pagination.current_page = page;
                me.listarArticulo(page,buscar,criterio);
            },
            registrarArticulo(){
                if(this.validarArticulo()){
                    return;
                }

                let me = this;

                axios.post('/articulo/registrar',{
                    'nombre': this.nombre,
                    'description': this.descripcion,
                    'codigo': this.codigo,
                    'stock': this.stock,
                    'idcategoria': this.idcategoria,
                    'precio_venta': this.precio_venta
                }).then(function (response){
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "articulo":{
                        switch(accion){
                            case 'registrar':{
                                this.modal = 1;
                                this.nombre = '';
                                this.descripcion = '';
                                this.idcategoria = '';
                                this.codigo = '';
                                this.precio_venta = 0;
                                this.stock = 0;
                                this.tituloModal = 'Registrar Articulo';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':{
                                this.articulo_id = data['id'];
                                this.idcategoria = data['idcategoria'];
                                this.codigo=data['codigo']
                                this.stock = data['stock'];
                                this.precio_venta = data['precio_venta']
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Articulo';
                                this.tipoAccion = 2;
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }
                this.selectCategoria();
                }
                
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal ='';
                this.idcategoria = 0;
                this.nombre_categoria = '';
                this.codigo = '';
                this.precio_venta = 0;
                this.nombre = '';
                this.stock = 0;
                this.descripcion = '';
                this.errorArticulo = 0;
            },
            validarArticulo(){
                this.errorArticulo = 0;
                this.errorMostrarMsjArticulo =[];
                if(!this.nombre) this.errorMostrarMsjArticulo.push("El nombre del articulo debe estar completo.");
                if(!this.stock) this.errorMostrarMsjArticulo.push("El stock del articulo debe estar completo.");
                if(this.categoria_id==0) this.errorMostrarMsjArticulo.push("La categoria del articulo debe estar completo.");
                if(this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

                return this.errorArticulo;

            },
            actualizarArticulo(){
                if(this.validarArticulo()){
                    return;
                }
                let me = this;

                axios.put('/articulo/actualizar',{
                    'id': this.articulo_id,
                    'nombre': this.nombre,
                    'description': this.descripcion,
                    'codigo': this.codigo,
                    'stock': this.stock,
                    'idcategoria': this.idcategoria,
                    'precio_venta': this.precio_venta
                }).then(function (response){
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            desactivarArticulo(data = []){
                this.nombre=data['nombre'];
                this.articulo_id = data['id'];
                let me = this;
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: 'Estas seguro que deseas Desactivar?',
                text: "Articulo: "+this.nombre,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivarlo!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    axios.put('/articulo/desactivar',{
                        'id' : this.articulo_id
                    }).then(function (response){
                        swalWithBootstrapButtons(
                            'Desactivado!',
                            'Tu registro fue Desactivado.',
                            'success'
                        );
                        me.listarArticulo(1,'','nombre');
                    }).catch(function (error){
                        swalWithBootstrapButtons(
                            'Error',
                            error.message,
                            'error'
                        )
                    });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        'Cancelado',
                        'No se hicieron cambios',
                        'error'
                    )
                }
                })
            },
            activarArticulo(data = []){
                this.nombre=data['nombre'];
                this.articulo_id=data['id'];
                let me = this;
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: 'Estas seguro que deseas Activar?',
                text: "Articulo: "+this.nombre,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Activarla!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    axios.put('/articulo/activar',{
                        'id' : this.articulo_id
                    }).then(function (response){
                        swalWithBootstrapButtons(
                            'Desactivado!',
                            'Tu registro fue Activado.',
                            'success'
                        );
                        me.listarArticulo(1,'','nombre');
                    }).catch(function (error){
                        swalWithBootstrapButtons(
                            'Error',
                            error.message,
                            'error'
                        )
                    });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        'Cancelado',
                        'No se hicieron cambios',
                        'error'
                    )
                }
                })
            },
            selectCategoria(){
                let me = this;
                var url='/categoria/selectCategoria';

                axios.get(url).then(function (response){
                    var respuesta = response.data
                    me.arrayCategoria = respuesta.categorias;
                }).catch(function (error){
                    console.log(error);
                });
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.listarArticulo(1,this.buscar,this.criterio);
        }
    }
</script>

<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>

