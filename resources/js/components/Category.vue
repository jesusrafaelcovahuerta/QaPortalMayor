<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Categorias
                <router-link to="/category/create" class="btn btn-success btn-icon-split" v-if="rols_permissions[12]">
                    <span class="icon text-white-50">
                      <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Crear</span>
                </router-link>
            </h1>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Default Card Example -->
                    <div class="card mb-4">
                        <div class="card-header">
                        Buscar
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="onSubmit" ref="searchDte">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Sección</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.section_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="section_post in section_posts" :key="section_post.section_id" :value="section_post.section_id">{{ section_post.section_title }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button
                                type="submit" class="btn btn-primary btn-icon-split text-right">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <span class="text">Buscar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                            <div v-if="rowsQuantity > 0 && rols_permissions[11]">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Título</th>
                                            <th>Alianza</th>
                                            <th>Sección</th>
                                            <th>Posición</th>
                                            <th>Estatus</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>{{ post.category_id }}</td>
                                            <td>{{ post.title }}</td>
                                            <td>{{ post.alliance }}</td>
                                            <td>{{ post.section_title }}</td>
                                            <td>{{ post.position }}</td>
                                            <td>
                                                <span class="badge badge-danger" v-if="post.status == 0">
                                                    Desactivado
                                                </span>
                                                <span class="badge badge-success" v-if="post.status == 1">
                                                    Activado
                                                </span>
                                            </td>
                                            <td>
                                                <router-link v-if="rols_permissions[13]" :to="`/category/edit/${post.category_id}`"  class="btn btn-primary btn-circle btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </router-link>
                                                <button v-if="rols_permissions[14]" v-on:click="deletePost(post.category_id, index)" class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button v-if="index != (rowsQuantity-1) && rols_permissions[35]" v-on:click="movePost(post.category_id, index+1, post.section_id)" class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button v-if="index != 0 && rols_permissions[35]" v-on:click="movePost(post.category_id, index-1, post.section_id)" class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-arrow-up"></i>
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
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        created() {
            this.getRols();
            this.storeAudit();
            this.getSectionList();
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
            getSectionList() {
                axios.get('/api/section/list?api_token='+App.apiToken)
                .then(response => {
                    this.section_posts = response.data.data;
                });
            },
            onSubmit() {
                this.loading = true;

                if(this.form.section_id == '') {
                    this.form.section_id = null;
                }

                if(this.form.section_id != null 
                ) {
                    axios.post('/api/category/search/'+ this.form.section_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                } else {
                    axios.get('/api/category?page='+this.currentPage+'&api_token='+App.apiToken)
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
                }
            },
            movePost(id, index, section_id) {
                this.loading = true; //the loading begin
                axios.get('/api/category/move/'+id+'/'+index+'/'+section_id+'?api_token='+App.apiToken).then(response => {
                    
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                    this.onSubmit();
                });

                let formData = new FormData();
                formData.append('page', 'MoveCategory - '+id);
                
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Categorias');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/category/destroy/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.onSubmit();
                        this.$awn.success("El registro ha sido borrado", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'DeleteCategory - '+id);
                
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

                    let formData = new FormData();
                    formData.append('page', 'ActivateCategory - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
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
                    rol_id: null,
                    section_id: null
                },
                branch_office_posts: [],
                supervisor_posts: [],
                section_posts: [],
                rol_id: this.rol_id,
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
