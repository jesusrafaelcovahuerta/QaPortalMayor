<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Usuario
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información</h6>
                </div>
                <div class="card-body" v-if="rols_permissions[26]">
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
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">RUT</label>
                                        <input
                                        type="text" 
                                        v-model="form.rut"
                                        class="form-control" 
                                        v-mask="'########-N'"
                                        placeholder="Ingresa el rut"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Nombres</label>
                                        <input
                                        type="text" 
                                        v-model="form.name" 
                                        class="form-control"
                                        placeholder="Ingresa el nombre"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Apellidos</label>
                                        <input
                                        type="text" 
                                        v-model="form.lastname" 
                                        class="form-control"
                                        placeholder="Ingresa el alias"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Alianza</label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.alliance_id"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-for="alliance_post in alliance_posts" :key="alliance_post.rut" :value="alliance_post.rut">{{ alliance_post.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Correo</label>
                                        <input
                                        type="text" 
                                        v-model="form.email" 
                                        class="form-control"
                                        placeholder="Ingresa el correo"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Teléfono</label>
                                        <input
                                        type="text" 
                                        v-mask="'(+56)#########'"
                                        v-model="form.phone" 
                                        class="form-control"
                                        placeholder="Ingresa el teléfono"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Contraseña</label>
                                        <input
                                        type="password" 
                                        v-model="form.password" 
                                        class="form-control"
                                        placeholder="Ingresa la contraseña"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Repetir la contraseña</label>
                                        <input
                                        type="password" 
                                        v-model="form.repassword" 
                                        class="form-control"
                                        placeholder="Ingresa nuevamente la contraseña"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Rol</label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.rol_id"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-if="rol_id == 3" :value="1">Administrador</option>
                                            <option :value="2">Alianza</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Pregunta de seguridad</label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.question_id"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option :value="1">¿Dónde nacistes?</option>
                                            <option :value="2">¿Cuál es el nombre de tu mamá?</option>
                                            <option :value="3">¿Cuál es el nombre de tu papá?</option>
                                            <option :value="4">¿Cuál es el nombre de tu mascota?</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Respuesta</label>
                                        <input
                                        type="text" 
                                        v-model="form.answer" 
                                        class="form-control"
                                        placeholder="Ingresa la respuesta"
                                        >
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
                                <router-link to="/user" class="btn btn-danger btn-icon-split">
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
            this.storeAudit();
            this.getAlliaceList();
            this.getRol();
        },
        data: function() {
            return {
                rols_permissions: {},
                errors: [],
                color: '#0A2787',
                loading: false,
                alliance_posts: [],
                form: {
                    rut: '',
                    name: '',
                    lastname: '',
                    alliance_id: null,
                    email: '',
                    phone: '',
                    password: '',
                    repassword: '',
                    rol_id: null,
                    question_id: null,
                    answer: ''
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
                formData.append('page', 'Crear Usuario');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getRol() {
                axios.get('/api/user/rol?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            },
            getAlliaceList() {
                axios.get('/api/alliance/list?api_token='+App.apiToken)
                .then(response => {
                    this.alliance_posts = response.data.data;
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
                    && this.form.lastname != ''
                    && this.form.alliance_id != null
                    && this.form.email != ''
                    && this.form.phone != ''
                    && this.form.password != ''
                    && this.form.repassword != ''
                    && this.form.password == this.form.repassword
                    && this.form.rol_id != null
                    && this.form.question_id != null
                    && this.form.answer != ''
                ) {
                    let formData = new FormData();
                    formData.append('name', this.form.name);
                    formData.append('rut', this.form.rut);
                    formData.append('name', this.form.name);
                    formData.append('lastname', this.form.lastname);
                    formData.append('alliance_id', this.form.alliance_id);
                    formData.append('email', this.form.email);
                    formData.append('phone', this.form.phone);
                    formData.append('password', this.form.password);
                    formData.append('rol_id', this.form.rol_id);
                    formData.append('question_id', this.form.question_id);
                    formData.append('answer', this.form.answer);

                    axios.post('/api/user/store?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Usuario Creado');
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/user');
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
                    if (this.form.lastname == '') {
                        this.errors.push('El apellido es obligatorio.');
                    }
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
                    if (this.form.rol_id == null) {
                        this.errors.push('El rol es obligatorio.');
                    }
                    if (this.form.question_id == null) {
                        this.errors.push('la pregunta es obligatoria.');
                    }
                    if (this.form.answer == '') {
                        this.errors.push('La respuesta es obligatoria.');
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
