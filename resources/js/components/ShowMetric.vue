<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Editar Sección
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
                                    <div class="col-sm-12">
                                        <label for="exampleInputEmail1">Título <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        maxlength="36"
                                        v-model="form.title" 
                                        class="form-control"
                                        placeholder="Ingresa el título"
                                        >
                                    </div>
                                    <span class="col-sm-12">{{charactersLeft}}</span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Color <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <v-input-colorpicker v-model="color" @change="handleChange" />
                                            </div>
                                            <div class="col-sm-10">
                                                <input
                                                type="text" 
                                                v-model="form.color" 
                                                class="form-control"
                                                placeholder="Ingresa el color"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Posición <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="number" 
                                        min="0"
                                        v-model="form.position" 
                                        class="form-control"
                                        placeholder="Ingresa la posición"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Tipo de Icono <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.icon_type_id"
                                        >
                                            <option :value="2">Ionic Icon</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Ionic Icon - <a href="https://ionicframework.com/docs/v3/ionicons/" target= "_blank">Ver iconos</a></label>
                                        <input
                                            type="text" 
                                            v-model="form.fai" 
                                            class="form-control"
                                            placeholder="Ingresa el icono"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿Es un enlace a una página externa? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.link_question_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6" v-if="form.link_question_id == 1">
                                        <label for="exampleInputEmail1">Url o enlace</label>
                                        <input
                                            type="text" 
                                            v-mask="'http://XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'"
                                            v-model="form.url" 
                                            class="form-control"
                                            placeholder="Ingresa la url o enlace"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿Es un video de Youtube? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.youtube_question_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6" v-if="form.youtube_question_id == 1">
                                        <label for="exampleInputEmail1">Id del Video</label>
                                        <input
                                            type="text" 
                                            v-model="form.video_id"
                                            class="form-control"
                                            placeholder="Ingresa el Id del Video"
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
                                <router-link to="/section" class="btn btn-danger btn-icon-split">
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
            this.getPost();
            this.getRol();
            this.storeAudit();
        },
        data: function() {
            return {
                errors: [],
                color: '#0A2787',
                loading: false,
                color: "#0f4c81",
                noFile: false,
                form: {
                    title: '',
                    color: '',
                    icon: '',
                    position: '',
                    icon_type_id: 2,
                    fai: '',
                    link_question_id: 2,
                    url: '',
                    video_id: '',
                    youtube_question_id: 2
                }
            }
        },
        methods: {
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'EditSection - Section Id: '+this.$route.params.id);
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPost() {
                axios.get('/api/section/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.$set(this.form, 'title', this.post.section_title);
                    this.$set(this, 'color', this.post.color);
                    this.$set(this.form, 'color', this.post.color);
                    this.$set(this.form, 'position', this.post.position);
                    this.$set(this.form, 'video_id', this.post.video_id);

                    if(this.post.video_id != 0) {
                        this.$set(this.form, 'youtube_question_id', 1);
                    } else {
                        this.$set(this.form, 'youtube_question_id', 2);
                    }

                    if(this.post.icon_type_id == 2) {
                        var icon = this.post.icon;
                        var icon_detail = icon.split(' ');
                        var icon_detail = icon_detail[1];
                        var icon_detail = icon_detail.split('-');
                        var icon = 'ios-'+icon_detail[2];
                        this.$set(this.form, 'fai', icon);
                    }
                    this.$set(this.form, 'icon_type_id', this.post.icon_type_id);
                    this.$set(this.form, 'link_question_id', this.post.link_question_id);
                    this.$set(this.form, 'url', this.post.url);
                });
            },
            handleChange() {
                this.form.color = this.color;
            },
            onFileChange(e){
                this.file = e.target.files[0];
                this.noFile = e.target.files.length;
            },
            onSubmit(e) {
                this.loading = true; //the loading begin
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                if(this.form.title != ''
                    && this.form.title.length <= 28
                    && this.form.color != ''
                    && this.form.icon_type_id != null
                    && this.form.position != ''
                ) {
                    let formData = new FormData();
                    formData.append('title', this.form.title);
                    formData.append('color', this.form.color);
                    formData.append('icon_type_id', this.form.icon_type_id);
                    if(this.form.icon_type_id == 1) {
                        formData.append('file', this.file);
                    } else {
                        formData.append('icon', this.form.fai);
                    }
                    formData.append('position', this.form.position);
                    formData.append('link_question_id', this.form.link_question_id);
                    formData.append('url', this.form.url);
                    formData.append('video_id', this.form.video_id);

                    axios.post('/api/section/update/'+ this.$route.params.id +'?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/section');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];
                    
                    if (this.form.title == '') {
                        this.errors.push('El título es obligatorio.');
                    }
                    if (this.form.title.length > 28) {
                        this.errors.push('El nombre debe tener menos de 28 caracteres.');
                    }
                    if (this.form.color == '') {
                        this.errors.push('El color es obligatorio.');
                    }
                    if (this.form.icon_type_id == null) {
                        this.errors.push('El tipo de icono es obligatorio.');
                    }
                    if (this.form.icon_type_id == 2 && this.form.fai == '') {
                        this.errors.push('El icono es obligatorio.');
                    }
                    if (this.form.position == '') {
                        this.errors.push('La posición es obligatoria.');
                    }

                    $('html,body').scrollTop(0);

                    e.preventDefault();
                }
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            }
        },
        computed: {
            isDisabled() {
                return true;
            },
            charactersLeft() {
                var char = this.form.title.length,
                    limit = 36;

                return (limit - char) + " / " + limit + " caracteres disponibles";
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
