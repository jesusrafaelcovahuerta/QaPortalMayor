<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Editar Usuario
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información</h6>
                </div>
                <div class="card-body" v-if="rols_permissions[27]">
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
                                    <span class="text">Actualizar</span>
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
            this.getAlliaceList();
            this.getPost();
            this.storeAudit();
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
                formData.append('page', 'Editar Usuario - Id del Usuario: '+this.$route.params.id);
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPost() {
                axios.get('/api/user/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.$set(this.form, 'rut', this.post.rut);
                    this.$set(this.form, 'name', this.post.name);
                    this.$set(this.form, 'lastname', this.post.lastname);
                    this.$set(this.form, 'alliance_id', this.post.alliance_id);
                    this.$set(this.form, 'email', this.post.email);
                    this.$set(this.form, 'phone', this.post.phone);
                    this.$set(this.form, 'question_id', this.post.question_id);
                    this.$set(this.form, 'answer', this.post.answer);
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
                    && this.form.question_id != null
                    && this.form.answer != ''
                ) {
                    let formData = new FormData();
                    formData.append('rut', this.form.rut);
                    formData.append('name', this.form.name);
                    formData.append('lastname', this.form.lastname);
                    formData.append('alliance_id', this.form.alliance_id);
                    formData.append('email', this.form.email);
                    formData.append('phone', this.form.phone);
                    formData.append('password', this.form.password);
                    formData.append('question_id', this.form.question_id);
                    formData.append('answer', this.form.answer);

                    axios.post('/api/user/update/'+ this.$route.params.id +'?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Usuario Actualizado - Id del Usuario: '+this.$route.params.id);
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                        
                        this.loading = false;
                        this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});
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
