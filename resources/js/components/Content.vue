<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Contenido 
                <router-link to="/content/create" class="btn btn-success btn-icon-split">
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
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Sección</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.section_id"
                                            @change="getCategoryList"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="section_post in section_posts" :key="section_post.section_id" :value="section_post.section_id">{{ section_post.section_title }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Categoría</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.category_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="category_post in category_posts" :key="category_post.category_id" :value="category_post.category_id">{{ category_post.title }}</option>
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
                            <div v-if="rowsQuantity > 0 && rols_permissions[15]">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Alianza</th>
                                            <th>Sección</th>
                                            <th>Categoría</th>
                                            <th>Inicio</th>
                                            <th>Termino</th>
                                            <th>Posición</th>
                                            <th>Estatus</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>{{ post.title }}</td>
                                            <td>{{ post.alliance }}</td>
                                            <td>{{ post.section }}</td>
                                            <td>{{ post.category }}</td>
                                            <td>{{ formatDate(post.start_date) }}</td>
                                            <td>{{ formatDate(post.end_date) }}</td>
                                            <td>{{ index+1 }}</td>
                                            <td>
                                                <span class="badge badge-danger" v-if="post.status == 0">
                                                    Desactivado
                                                </span>
                                                <span class="badge badge-success" v-if="post.status == 1">
                                                    Activado
                                                </span>
                                                <span class="badge badge-warning" v-if="post.status == 2">
                                                    En espera
                                                </span>
                                            </td>
                                            <td>
                                                <router-link v-if="post.status == 1 && rols_permissions[17]" :to="`/content/edit/${post.content_id}`"  class="btn btn-primary btn-circle btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </router-link>
                                                <router-link v-if="post.status == 2" :to="`/content/edit/${post.content_id}`"  class="btn btn-primary btn-circle btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </router-link>
                                                <button v-if="post.status == 1 && rols_permissions[37]" v-on:click="stopPost(post.content_id, index)" class="btn btn-warning btn-circle btn-sm">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                                <button v-if="post.status == 2 && rols_permissions[38]" v-on:click="playPost(post.content_id, index)" class="btn btn-warning btn-circle btn-sm">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button v-if="post.status == 1 && rols_permissions[18]" v-on:click="deletePost(post.content_id, index)" class="btn btn-danger btn-circle btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button v-if="index != (rowsQuantity-1) && rols_permissions[36]" v-on:click="movePost(post.content_id, index+1, post.category_id)" class="btn btn-success btn-circle btn-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <button v-if="index != 0 && rols_permissions[36]" v-on:click="movePost(post.content_id, index-1, post.category_id)" class="btn btn-success btn-circle btn-sm">
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
    import moment from 'moment'
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        created() {
            this.getRols();
            this.getAlliaceList();
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
            movePost(id, index, category_id) {
                this.loading = true;
                axios.get('/api/content/move/'+id+'/'+index+'/'+category_id+'?api_token='+App.apiToken).then(response => {
                    
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                    this.onSubmit();
                });

                let formData = new FormData();
                formData.append('page', 'MoveContent - '+id);
                
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getCategoryList() {
                axios.get('/api/category/list/'+this.form.section_id+'?api_token='+App.apiToken)
                .then(response => {
                    this.category_posts = response.data.data;
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
                if(this.form.title == '') {
                    this.form.title = null;
                }

                if(this.form.alliance_id == '') {
                    this.form.alliance_id = null;
                }

                if(this.form.section_id == '') {
                    this.form.section_id = null;
                }

                if(this.form.category_id == '') {
                    this.form.category_id = null;
                }

                if(this.form.title != null 
                || this.form.alliance_id != null 
                || this.form.section_id != null 
                || this.form.category_id != null 
                ) {
                    axios.post('/api/content/search/'+ this.form.section_id +'/'+ this.form.category_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
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
                    axios.get('/api/content?page='+this.currentPage+'&api_token='+App.apiToken)
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
            getAlliaceList() {
                axios.get('/api/alliance/list?api_token='+App.apiToken)
                .then(response => {
                    this.alliance_posts = response.data.data;
                });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Contenidos');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatDate(value) {
                if(value != '' && value != null && value != '0000-00-00') {
                    return moment(value).format('DD-MM-YYYY');
                } else {
                    return '';
                }
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/content/destroy/'+id+'?api_token='+App.apiToken).then(response => {
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
                    formData.append('page', 'DeleteContent - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            stopPost(id, index) {
                if(confirm("¿Realmente usted quiere detener la publicación?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/content/stop/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.onSubmit();
                        this.$awn.success("El registro ha sido detenido", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'StopContent - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            playPost(id, index) {
                if(confirm("¿Realmente usted quiere activar la publicación?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/content/play/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.onSubmit();
                        this.$awn.success("El registro ha sido activado", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'PlayContent - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            acceptPost(id, index) {
                if(confirm("¿Realmente usted quiere aceptar el contenido?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/content/accept/'+id+'?api_token='+App.apiToken).then(response => {
                        
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.onSubmit();
                        this.$awn.success("El registro ha sido aceptado", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'AcceptContent - '+id);
                
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
                alliance_posts: [],
                section_posts: [],
                category_posts: [],
                form: {
                    rol_id: null,
                    alliance_id: null,
                    title: '',
                    section_id: null,
                    category_id: null
                },
                rol_id: this.rol_id,
                branch_office_posts: [],
                supervisor_posts: [],
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
