<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Usuarios 
                <router-link to="/user/create" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Crear</span>
                </router-link>
            </h1>
            <hr>
            
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div v-if="loading">
                            <center>
                                <clip-loader :color="color"></clip-loader>
                            </center>
                        </div>
                        <div v-else>
                            <div v-if="rowsQuantity > 0 && rols_permissions[25]">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Rut</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Rol</th>
                                            <th>Alianza</th>
                                            <th>Correo</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>{{ post.rut }}</td>
                                            <td>{{ post.name }}</td>
                                            <td>{{ post.lastname }}</td>
                                            <td>
                                                <span class="badge badge-danger" v-if="post.status == 0">
                                                    {{ post.rol }}
                                                </span>
                                                <span class="badge badge-warning" v-if="post.status == 1">
                                                    {{ post.rol }}
                                                </span>
                                                <span class="badge badge-info" v-if="post.status == 2">
                                                    {{ post.rol }}
                                                </span>
                                            </td>
                                            <td>{{ post.alliance }}</td>
                                            <td>{{ post.email }}</td>
                                            <td>
                                                <span class="badge badge-danger" v-if="post.status == 0">
                                                    Desactivado
                                                </span>
                                                <span class="badge badge-success" v-if="post.status == 1">
                                                    Activado
                                                </span>
                                            </td>
                                            <td>
                                                <router-link v-if="rols_permissions[27]" :to="`/user/edit/${post.rut}`" v-if="post.rol_id != 1 || rut == post.rut || rol_id == 3"  class="btn btn-primary btn-circle btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </router-link>
                                                <button v-if="post.status == 1 && rol_id == 3 && rols_permissions[28]" v-on:click="deletePost(post.rut, index)" class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button v-if="post.status == 0 && rols_permissions[39]" v-on:click="activatePost(post.rut, index)" class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Resultado</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Resultado</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">No hay resultados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <v-pagination v-model="currentPage" 
                            :page-count="total"
                            @input='getPosts'
                            :classes="bootstrapPaginationClasses"
                            :labels="paginationAnchorTexts"
                            ></v-pagination>

        </div>
        
    </div>
    
</template>

<script>
    import vPagination from 'vue-plain-pagination';
    import moment from 'moment'
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        created() {
            this.getRols();
            this.getPosts();
            this.getRol();
            this.storeAudit();
            this.getUser();
        },
        methods: {
            getRols() {
                axios.get('/api/user/rol?api_token=' + App.apiToken)
                    .then(response => {
                        this.rols_permissions = {}; // Initialize as an object

                        response.data.data.forEach(item => {
                            this.rols_permissions[item.permission_id] = true; // Set as true
                        });

                    });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Usuarios');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPosts() {
                this.loading = true;

                axios.get('/api/user?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.rowsQuantity = response.data.data.total;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            formatDate(value) {
                if(value != '' && value != null && value != '0000-00-00') {
                    return moment(value).format('DD-MM-YYYY');
                } else {
                    return '';
                }
            },
            getUser() {
                axios.get('/api/user/rut?api_token='+App.apiToken)
                .then(response => {
                    this.rut = response.data.data.rut;
                });
            },
            getRol() {
                axios.get('/api/user/rol?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere desactivar el registro?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/user/destroy/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.getPosts();
                        this.$awn.success("El registro ha sido desactivado", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'DeleteUser - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            activatePost(id, index) {
                if(confirm("¿Realmente usted quiere activar el registro?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/user/activate/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.getPosts();
                        this.$awn.success("El registro ha sido activado", {labels: {success: "Éxito"}});
                    });
                }
            }
        },
        components: { 
            vPagination,
            ClipLoader
        },
        data: function() {
            return {
                rols_permissions: {},
                color: '#0A2787',
                loading: false,
                form: {
                    rol_id: null
                },
                branch_office_posts: [],
                supervisor_posts: [],
                rol_id: this.rol_id,
                rut: this.rut,
                postsSelected: "",
                posts: [],
                currentPage: 1,
                total: 0,
                rowsQuantity: '',
                bootstrapPaginationClasses: {
                    ul: 'pagination',
                    li: 'page-item',
                    liActive: 'active',
                    liDisable: 'disabled',
                    button: 'page-link'  
                },
                paginationAnchorTexts: {
                    first: '',
                    prev: '&laquo;',
                    next: '&raquo;',
                    last: ''
                },
                mobilepaginationAnchorTexts: {
                    first: '',
                    prev: '',
                    next: '',
                    last: ''
                }
            }
        }
    }
</script>
