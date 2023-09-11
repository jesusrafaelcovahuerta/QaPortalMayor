<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Rol
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
                                        <label for="exampleInputEmail1">Nombre del Rol <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.rol" 
                                        maxlength="36"
                                        class="form-control"
                                        placeholder="Ingresa el rol"
                                        >
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Permisos <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.permission_id" multiple
                                        >
                                            <option v-for="permission_post in permission_posts" :key="permission_post.permission_id" :value="permission_post.permission_id">{{ permission_post.permission }}</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <button 
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Guardar</span>
                                </button>
                                <router-link to="/rol" class="btn btn-danger btn-icon-split">
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
    import InputColorPicker from 'vue-native-color-picker';

    export default {
        components: {
            ClipLoader,
            "v-input-colorpicker": InputColorPicker
        },
        created() {
            this.storeAudit();
            this.getPosts();
        },
        data: function() {
            return {
                errors: [],
                permission_posts: [],
                loading: false,
                noFile: false,
                form: {
                    rol: '',
                    permission_id: null,
                }
            }
        },
        methods: {
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Crear Rol');
               
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

                axios.get('/api/permission?api_token='+App.apiToken)
                .then(response => {
                    this.permission_posts = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            onSubmit(e) {
                this.loading = true; //the loading begin
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                if(this.form.rol != '' && this.form.permission_id != null) {
                    let formData = new FormData();
                    formData.append('rol', this.form.rol);
                    formData.append('permissions', this.form.permission_id);

                    axios.post('/api/rol/store?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Rol Creado');
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/rol');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];
                    
                    if (this.form.rol == '') {
                        this.errors.push('El nombre del rol es obligatorio.');
                    }

                    if (this.form.permission_id == null) {
                        this.errors.push('El permiso es obligatorio.');
                    }

                    window.scrollTo(0, 0);

                    e.preventDefault();
                }
            },

        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
