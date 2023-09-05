<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Editar Alianza
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
                <div class="card-body" v-if="rols_permissions[4]">
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
                                    <div class="col-sm-3">
                                        <label for="exampleInputEmail1">RUT <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.rut"
                                        class="form-control" 
                                        v-mask="'########-N'"
                                        placeholder="Ingresa el rut"
                                        >
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Nombre <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.name" 
                                        class="form-control"
                                        placeholder="Ingresa el nombre"
                                        >
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="exampleInputEmail1">Alias <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.alias" 
                                        class="form-control"
                                        placeholder="Ingresa el alias"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Contácto <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.contact" 
                                        class="form-control"
                                        placeholder="Ingresa el contácto"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Correo <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.email" 
                                        class="form-control"
                                        placeholder="Ingresa el correo"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Teléfono <h6 class="m-0 text-danger float-right">*</h6></label>
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
                                        <label for="exampleInputEmail1">Fecha Inicio <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="date" 
                                        v-model="form.start_date" 
                                        class="form-control"
                                        placeholder="Ingresa la fecha de inicio"
                                        >
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Fecha Termino</label>
                                        <input
                                        type="date" 
                                        v-model="form.end_date" 
                                        class="form-control"
                                        placeholder="Ingresa la fecha de termino"
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
            this.getRols();
            this.getPost();
            this.storeAudit();
        },
        data: function() {
            return {
                rols_permissions: {},
                errors: [],
                color: '#0A2787',
                loading: false,
                form: {
                    rut: '',
                    alias: '',
                    contact: '',
                    email: '',
                    phone: '',
                    start_date: '',
                    end_date: ''
                }
            }
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
                formData.append('page', 'Editar Alianza - Id de la Alianza: '+this.$route.params.id);
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPost() {
                axios.get('/api/alliance/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.$set(this.form, 'rut', this.post.rut);
                    this.$set(this.form, 'name', this.post.name);
                    this.$set(this.form, 'alias', this.post.alias);
                    this.$set(this.form, 'contact', this.post.contact);
                    this.$set(this.form, 'email', this.post.contact_email);
                    this.$set(this.form, 'phone', this.post.contact_phone);
                    this.$set(this.form, 'start_date', this.post.start_date);
                    this.$set(this.form, 'end_date', this.post.end_date);
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
                    && this.form.name != ''
                    && this.form.alias != ''
                    && this.form.contact != ''
                    && this.form.email != ''
                    && this.form.phone != ''
                    && this.form.start_date != ''
                ) {
                    let formData = new FormData();
                    formData.append('name', this.form.name);
                    formData.append('rut', this.form.rut);
                    formData.append('alias', this.form.alias);
                    formData.append('contact', this.form.contact);
                    formData.append('email', this.form.email);
                    formData.append('phone', this.form.phone);
                    formData.append('start_date', this.form.start_date);
                    formData.append('end_date', this.form.end_date);

                    axios.post('/api/alliance/update/'+ this.$route.params.id +'?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Alianza Actualizada - Id de la Alianza: '+this.$route.params.id);
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/alliance');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];
                    
                    if (this.form.rut == '') {
                        this.errors.push('El rut es obligatorio.');
                    }
                    if (this.form.rut.length != 10) {
                        this.errors.push('El rut no tiene la longitud correcta.');
                    }
                    if (this.form.name == '') {
                        this.errors.push('El nombre es obligatorio.');
                    }
                    if (this.form.alias == '') {
                        this.errors.push('El alias es obligatorio.');
                    }
                    if (this.form.client == '') {
                        this.errors.push('El contacto es obligatorio.');
                    }
                    if (this.form.email == '' || this.form.email == null) {
                        this.errors.push('El correo es obligatorio.');
                    }
                    if (this.form.phone == null) {
                        this.errors.push('El teléfono es obligatorio.');
                    }
                    if (this.form.start_date == '' || this.form.start_date == null) {
                        this.errors.push('La fecha de inicio es obligatoria.');
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
