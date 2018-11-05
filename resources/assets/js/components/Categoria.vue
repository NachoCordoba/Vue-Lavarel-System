<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" class="btn btn-secondary" @click="abrirModal('categoria','registrar')" >
                            <i class="icon-plus"></i>  &nbsp; Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" id="opcion" name="opcion">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('categoria','actualizar',categoria)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="categoria.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                        <div v-if="categoria.condicion">
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
                                    <a class="page-link" @click.prevent="cambiarPagina(pagination.current_page - 1)">Anterior</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' :'']">
                                    <a class="page-link" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" @click.prevent="cambiarPagina(pagination.current_page + 1)">Siguiente</a>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input  v-model="descripcion" class="form-control" placeholder="Ingrese Descripcion">
                                    </div>
                                </div>
                                <div v-show="errorCategoria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="registrarCategoria()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarCategoria()">Actualizar</button>
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
                categoria_id : 0 ,
                nombre :'',
                descripcion:'',
                arrayCategoria:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: '',
                errorCategoria : 0,
                errorMostrarMsjCategoria : [],
                pagination :{
                    'total'         :  0,
                    'current_page'  :  0,
                    'per-page'      :  0,
                    'last_page'     :  0,
                    'from'          :  0,
                    'to'            :  0
                },
                offset : 3
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
            listarCategoria(page){
                let me = this;
                var url='/categoria?page='+page;

                axios.get(url).then(function (response){
                    var respuesta = response.data
                    me.arrayCategoria = respuesta.categorias.data;
                    me.pagination = respuesta.pagination;
                }).catch(function (error){
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;

                me.pagination.current_page = page;
                me.listarCategoria(page);
            },
            registrarCategoria(){
                if(this.validarCategoria()){
                    return;
                }

                let me = this;

                axios.post('/categoria/registrar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCategoria();
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    case "categoria":{
                        switch(accion){
                            case 'registrar':{
                                this.modal = 1;
                                this.nombre = '';
                                this.descripcion = '';
                                this.tituloModal = 'Registrar Categoria';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':{
                                this.categoria_id = data['id']
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Categoria';
                                this.tipoAccion = 2;
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion']
                            }
                        }
                    }
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal ='';
                this.nombre ='';
                this.descripcion = '';
            },
            validarCategoria(){
                this.errorCategoria = 0;
                this.errorMostrarMsjCategoria =[];
                if(!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la categoria debe estar completo.");
                if(this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;

                return this.errorCategoria;

            },
            actualizarCategoria(){
                if(this.validarCategoria()){
                    return;
                }
                let me = this;

                axios.put('/categoria/actualizar',{
                    'id' : this.categoria_id,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }).then(function (response){
                    me.cerrarModal();
                    me.listarCategoria();
                }).catch(function (error){
                    console.log(error);
                });
            },
            desactivarCategoria(data = []){
                this.nombre=data['nombre'];
                this.categoria_id=data['id'];
                let me = this;
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: 'Estas seguro que deseas Desactivar?',
                text: "Categoria: "+this.nombre,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivarlo!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    axios.put('/categoria/desactivar',{
                        'id' : this.categoria_id
                    }).then(function (response){
                        swalWithBootstrapButtons(
                            'Desactivado!',
                            'Tu registro fue Desactivado.',
                            'success'
                        );
                        me.listarCategoria();
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
            activarCategoria(data = []){
                this.nombre=data['nombre'];
                this.categoria_id=data['id'];
                let me = this;
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: 'Estas seguro que deseas Activar?',
                text: "Categoria: "+this.nombre,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Activarla!',
                cancelButtonText: 'No, cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    axios.put('/categoria/activar',{
                        'id' : this.categoria_id
                    }).then(function (response){
                        swalWithBootstrapButtons(
                            'Desactivado!',
                            'Tu registro fue Activado.',
                            'success'
                        );
                        me.listarCategoria();
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
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.listarCategoria();
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

