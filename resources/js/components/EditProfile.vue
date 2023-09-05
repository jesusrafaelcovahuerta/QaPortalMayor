<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Perfil
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información</h6>
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
                                        <label for="exampleInputEmail1">Correo</label>
                                        <input
                                        type="text" 
                                        v-model="form.email" 
                                        class="form-control"
                                        placeholder="Ingresa el correo"
                                        >
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Teléfono</label>
                                        <input
                                        type="number" 
                                        v-model="form.phone" 
                                        class="form-control"
                                        placeholder="Ingresa el teléfono"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Contraseña</label>
                                        <input
                                        type="password" 
                                        v-model="form.password" 
                                        class="form-control"
                                        placeholder="Ingresa la contraseña"
                                        >
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Repetir la contraseña</label>
                                        <input
                                        type="password" 
                                        v-model="form.repassword" 
                                        class="form-control"
                                        placeholder="Ingresa nuevamente la contraseña"
                                        >
                                    </div>
                                </div>
                                <button 
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Actualizar</span>
                                </button>
                                <router-link to="/account" class="btn btn-danger btn-icon-split">
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
            this.storeAudit();
        },
        data: function() {
            return {
                errors: [],
                color: '#0A2787',
                loading: false,
                alliance_posts: [],
                form: {
                    email: '',
                    phone: '',
                    password: '',
                    repassword: ''
                }
            }
        },
        methods: {
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Editar Perfil - Id del Perfil: '+this.$route.params.id);
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            onSubmit(e) {
                this.loading = true;
                e.preventDefault();
                let currentObj = this;

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                if(this.form.rut != ''
                    && this.form.email != ''
                    && this.form.phone != ''
                    && this.form.password != ''
                    && this.form.repassword != ''
                    && this.form.password == this.form.repassword
                ) {
                    let formData = new FormData();
                    formData.append('email', this.form.email);
                    formData.append('phone', this.form.phone);
                    formData.append('password', this.form.password);

                    axios.post('/api/user/update/?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Perfil Actualizado - Id del Perfil: '+this.$route.params.id);
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                        this.loading = false;
                        this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});
                        this.$router.push('/account');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];
                    
                    if (this.form.email == '' || this.form.email == null) {
                        this.errors.push('El correo es obligatorio.');
                    }
                    if (this.form.phone == null) {
                        this.errors.push('El teléfono es obligatorio.');
                    }
                    if (this.form.password == '' || this.form.repassword == '') {
                        this.errors.push('La contraseña es obligatoria.');
                    }
                    if (this.form.password != this.form.repassword) {
                        this.errors.push('Las contraseñas deben coincidir.');
                    }

                    $('html,body').scrollTop(0);

                    e.preventDefault();
                }
            },

        },
        computed: {
            isDisabled() {
                return true;
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
