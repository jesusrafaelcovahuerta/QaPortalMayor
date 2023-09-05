<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Resultado de Encuestas 
                <button class="btn btn-primary">
                    <export-excel
                        :data="json_data">
                        Descargar
                        <i class="fas fa-arrow-down"></i>
                    </export-excel>
                </button>
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
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Pregunta</th>
                                        <th>Si</th>
                                        <th>No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ posts[index].question }}</td>
                                        <td>{{ posts[index].yes_answer }} %</td>
                                        <td>{{ posts[index].no_answer }} %</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        
    </div>
    
</template>

<script>
    import vPagination from 'vue-plain-pagination';
    import moment from 'moment'
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        created() {
            this.getPosts();
            this.getRol();
            this.getExcel();
            this.storeAudit();
        },
        methods: {
            getExcel() {
                this.loading = true;

                axios.get('/api/poll/excel/'+ this.$route.params.id +'?api_token='+App.apiToken)
                .then(response => {
                    this.json_data = response.data.data;
                    console.log(this.json_data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Resultado de la Encuesta');
               
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

                axios.get('/api/poll/result/'+ this.$route.params.id +'?api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data;
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
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            }
        },
        components: { 
            vPagination,
            ClipLoader
        },
        data: function() {
            return {
                color: '#0A2787',
                loading: false,
                form: {
                    rol_id: null
                },
                branch_office_posts: [],
                supervisor_posts: [],
                rol_id: this.rol_id,
                postsSelected: "",
                json_data: [],
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
