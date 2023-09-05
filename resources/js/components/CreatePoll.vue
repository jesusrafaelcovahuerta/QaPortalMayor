<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Encuesta
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <h6 class="m-0 font-weight-bold text-primary">Información</h6>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="m-0 text-danger float-right">* Campos Obligatorios</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div v-if="loading">
                            <center>
                                <clip-loader :color="color"></clip-loader>
                            </center>
                        </div>
                        <div v-else>
                            <form @submit.prevent="onSubmit" ref="createBill" enctype="multipart/form-data">
                                <div v-if="errors.length" class="alert alert-danger" role="alert">
                                    <b>Por favor, corrija los siguientes errores:</b>
                                    <ul>
                                        <li v-for="error in errors">{{ error }}</li>
                                    </ul>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Título <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.title" 
                                        maxlength="100"
                                        class="form-control"
                                        placeholder="Ingresa el nombre"
                                        >
                                        <span class="col-sm-12">{{charactersLeft}}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Sección <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        @change="getCategories"
                                        v-model="form.section_id"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-for="section_post in section_posts" :key="section_post.section_id" :value="section_post.section_id">{{ section_post.section_title }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Categoría <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        @change="getContents"
                                        v-model="form.category_id"
                                        >
                                            <option :value="null">-No Aplica-</option>
                                            <option v-for="category_post in category_posts" :key="category_post.category_id" :value="category_post.category_id">{{ category_post.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Contenido <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.content_id"
                                        >
                                            <option :value="null">-No Aplica-</option>
                                            <option v-for="content_post in content_posts" :key="content_post.content_id" :value="content_post.content_id">{{ content_post.title }}</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <a
                                        @click="addEntranceProduct(k)" class="btn btn-primary text-white btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Agregar Pregunta</span>
                                    </a>
                                </div>

                                <table class="table borderless" id="dataTable" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr v-for="(input,k) in entrance_inputs" :key="k">
                                            <td>
                                                <label for="exampleInputEmail1">Pregunta</label>
                                                <input
                                                    type="text" 
                                                    v-model="input.question"
                                                    class="form-control"
                                                    placeholder="Ingresa la pregunta"
                                                    >
                                            </td>
                                            <td>
                                                <label for="exampleFormControlSelect1">Tipo de Respuesta</label>
                                                <select class="form-control" id="exampleFormControlSelect1"
                                                            v-model="input.answer_type_id"
                                                            >
                                                    <option :value="null">-Seleccionar-</option>
                                                    <option :value="1">Si y No</option>
                                                    <option :value="2">Texto</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div style="padding-top:35px !important;">
                                                </div>
                                                <a
                                                    @click="removeEntranceProduct(k)" class="btn btn-warning btn-circle btn-sm">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-times text-white-50"></i>
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <button 
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Guardar</span>
                                </button>
                                <router-link to="/poll" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancelar</span>
                                </router-link>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    export default {
        components: {
            ClipLoader
        },
        created() {
            this.getSectionList();
            this.storeAudit();
        },
        data: function() {
            return {
                entrance_inputs: [{
                    question: null,
                    answer_type_id: null
                }],
                k: 0,
                j: 0,
                i: 0,
                n: 0,
                errors: [],
                color: '#0A2787',
                loading: false,
                section_posts: [],
                category_posts: [],
                content_posts: [],
                form: {
                    title: '',
                    section_id: null,
                    content_id: null,
                    category_id: null
                }
            }
        },
        methods: {
            getCategories() {
                axios.get('/api/category/list/'+this.form.section_id+'?api_token='+App.apiToken)
                .then(response => {
                    this.category_posts = response.data.data;
                });
            },
            getContents() {
                axios.get('/api/content/list/'+this.form.category_id+'?api_token='+App.apiToken)
                .then(response => {
                    this.content_posts = response.data.data;
                });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Create Encuesta');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            addEntranceProduct () {
                this.entrance_inputs.push({
                    question: null,
                    answer_type_id: null
                })
                console.log(this.inputs)
            },
            removeEntranceProduct (index) {
                this.entrance_inputs.splice(index, 1)
            },
            getSectionList() {
                axios.get('/api/section/list?api_token='+App.apiToken)
                .then(response => {
                    this.section_posts = response.data.data;
                });
            },
            onSubmit(e) {
                this.loading = true;
                e.preventDefault();
                let currentObj = this;

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                if(this.form.title != ''
                ) {
                    let formData = new FormData();
                    formData.append('title', this.form.title);
                    formData.append('section_id', this.form.section_id);
                    formData.append('category_id', this.form.category_id);
                    formData.append('content_id', this.form.content_id);
                    formData.append('informations', JSON.stringify(this.entrance_inputs));

                    axios.post('/api/poll/store?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Encuesta Creada');
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/poll');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];
                    
                    if (this.form.title == '') {
                        this.errors.push('El título es obligatorio.');
                    }

                    $('html,body').scrollTop(0);

                    e.preventDefault();
                }
            },

        },
        computed: {
            isDisabled() {
                return true;
            },
            charactersLeft() {
                var char = this.form.title.length,
                    limit = 100;

                return (limit - char) + " / " + limit + " caracteres disponibles";
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
