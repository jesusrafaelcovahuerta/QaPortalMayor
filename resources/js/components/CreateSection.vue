<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Sección
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
                <div class="card-body" v-if="rols_permissions[8]">
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
                                        maxlength="36"
                                        class="form-control"
                                        placeholder="Ingresa el título"
                                        >
                                        <span class="col-sm-12">{{charactersLeft}}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Sub-título</label>
                                        <input
                                        type="text" 
                                        v-model="form.subtitle" 
                                        maxlength="30"
                                        class="form-control"
                                        placeholder="Ingresa el subtítulo"
                                        >
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
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
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Posición <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="number" 
                                        v-model="form.position" 
                                        min="0"
                                        class="form-control"
                                        placeholder="Ingresa la posición"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Google Tag <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.google_tag" 
                                        class="form-control"
                                        placeholder="Ingresa el google tag"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">¿Tiene Georeferenciación? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.georeferencing_type_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" v-if="form.georeferencing_type_id == 1">
                                        <label for="exampleInputEmail1">Región <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.region_id" multiple
                                        @change="getCommunes"
                                        >
                                            <option :value="1000">Todas las regiones y comunas</option>
                                            <option v-for="region_post in region_posts" :key="region_post.region_id" :value="region_post.region_id">{{ region_post.region }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" v-if="form.georeferencing_type_id == 1">
                                        <label for="exampleInputEmail1">Comuna</label>
                                        <select class="form-control" id="exampleFormControlSelect1" v-model="form.commune_id"  multiple>
                                            <option :value="null" v-if="commune_posts.length == 0">No ha seleccionado una región</option>
                                            <option v-for="commune_post in commune_posts" :key="commune_post.commune_id" :value="commune_post.commune_id">{{ commune_post.commune }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿La sección tiene icono? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.icon_available_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                        <input type="hidden" v-model="form.icon_type_id">
                                    </div>
                                    <div class="col-sm-6" v-if="form.icon_available_id == 1">
                                        <label for="exampleInputEmail1">Fa Icon - <a href="https://fontawesome.com/icons" target= "_blank">Ver iconos</a></label>
                                        <input
                                            type="text" 
                                            v-model="form.fai" 
                                            class="form-control"
                                            placeholder="Ingresa el icono"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.link_question_id == 2 && form.iframe_question_id == 2 && form.youtube_question_id == 2 && form.open_app_id == 2">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿Es directo a Contenido? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.direct_content_question_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.direct_content_question_id == 2 && form.iframe_question_id == 2 && form.youtube_question_id == 2 && form.open_app_id == 2">
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
                                            v-model="form.url" 
                                            v-mask="'http://XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'"
                                            class="form-control"
                                            placeholder="Ingresa la url o enlace"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.direct_content_question_id == 2 && form.open_app_id == 2 && form.youtube_question_id == 2 && form.link_question_id == 2">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿Es un Iframe? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.iframe_question_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6" v-if="form.iframe_question_id == 1">
                                        <label for="exampleInputEmail1">Url del Iframe</label>
                                        <input
                                            type="text" 
                                            v-model="form.iframe"
                                            class="form-control"
                                            placeholder="Ingresa la url o enlace"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.direct_content_question_id == 2 && form.open_app_id == 2 && form.iframe_question_id == 2 && form.link_question_id == 2">
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
                                        <label for="exampleInputEmail1">URL del Video</label>
                                        <input
                                            type="text" 
                                            v-model="form.video_id"
                                            class="form-control"
                                            placeholder="Ingresa el Id del Video"
                                        >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="form.direct_content_question_id == 2 && form.youtube_question_id == 2 && form.iframe_question_id == 2 && form.link_question_id == 2">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">¿Va a abrir una aplicación? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.open_app_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" v-if="form.open_app_id == 1">
                                        <label for="exampleInputEmail1">¿Qué método va a utilizar? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.open_app_version_id"
                                        >
                                            <option value="">- Seleccione -</option>
                                            <option :value="1">URI</option>
                                            <option :value="2">URL</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" v-if="form.open_app_version_id == 1">
                                        <label for="exampleInputEmail1">URI a Compartir <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                            type="text" 
                                            v-model="form.open_app_uri_url"
                                            class="form-control"
                                            placeholder="Ingresa la uri para abrir la aplicación"
                                        >
                                    </div>
                                    <div class="col-sm-4" v-if="form.open_app_version_id == 2">
                                        <label for="exampleInputEmail1">URL a Compartir <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                            type="text" 
                                            v-model="form.open_app_uri_url"
                                            class="form-control"
                                            placeholder="Ingresa la url para abrir la aplicación"
                                        >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.open_app_id == 1 && form.open_app_version_id != ''">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">URL si es Computador <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                            type="text" 
                                            v-model="form.open_app_desktop_url"
                                            class="form-control"
                                            placeholder="Ingresa la uri para abrir la aplicación"
                                        >
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">URL si no está Instalada <h6 class="m-0 text-danger float-right">*</h6> (Google Play Store)</label>
                                        <input
                                            type="text" 
                                            v-model="form.open_app_not_installed"
                                            class="form-control"
                                            placeholder="Ingresa la url para abrir la aplicación"
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
            this.getRols();
            this.storeAudit();
            this.getRegions();
        },
        data: function() {
            return {
                rols_permissions: {},
                errors: [],
                region_posts: [],
                commune_posts: [],
                color: '#0A2787',
                loading: false,
                noFile: false,
                form: {
                    title: '',
                    georeferencing_type_id: 2,
                    color: '#0b2784',
                    icon: '',
                    position: '',
                    icon_type_id: 2,
                    fai: '',
                    link_question_id: 2,
                    video_id: '',
                    youtube_question_id: 2,
                    google_tag: '',
                    subtitle: '',
                    iframe_question_id: 2,
                    iframe: '',
                    direct_content_question_id: 2,
                    icon_available_id: 2,
                    region_id: null,
                    commune_id: null,
                    whatsapp_url_to_share: '',
                    whatsatpp_share_id: 1,
                    open_app_id: 2,
                    open_app_version_id: '',
                    open_app_uri_url: '',
                    open_app_desktop_url: '',
                    open_app_not_installed: '',
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
            getRegions() {
                this.loading = true;

                axios.get('/api/region')
                .then(response => {
                    this.region_posts = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            getCommunes() {
                this.loading = true;

                this.commune_posts = []

                var region_data = String(this.form.region_id);

                const region_ids = region_data.split(',');

                for (const region_id of region_ids) {
                    console.log(region_id)
                    axios.get('/api/commune/' + region_id)
                        .then(response => {
                            this.commune_posts = this.commune_posts.concat(response.data.data);
                        })
                        .catch(error => {
                            console.log(error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Crear Sección');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
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
                && this.form.color != ''
                    && this.form.google_tag != ''
                    && ((this.form.fai != '' && this.form.icon_available_id == 1) || this.form.icon_available_id == 2)
                    && this.form.position != ''
                    && this.form.direct_content_question_id != ''
                    && this.form.link_question_id != ''
                    && this.form.iframe_question_id
                    && this.form.youtube_question_id
                    && ((this.form.georeferencing_type_id == 2) || (this.form.georeferencing_type_id == 1 && this.form.region_id != null))
                    && ((this.form.open_app_id == 2) || (this.form.open_app_id == 1 && this.form.open_app_version_id != '' && this.form.open_app_uri_url != '' && this.form.open_app_desktop_url != '' && this.form.open_app_not_installed != ''))
                ) {
                    let formData = new FormData();
                    formData.append('title', this.form.title);
                    formData.append('color', this.form.color);
                    formData.append('icon_type_id', this.form.icon_type_id);
                    formData.append('icon', this.form.fai);
                    formData.append('position', this.form.position);
                    formData.append('link_question_id', this.form.link_question_id);
                    formData.append('url', this.form.url);
                    formData.append('video_id', this.form.video_id);
                    formData.append('subtitle', this.form.subtitle);
                    formData.append('iframe', this.form.iframe);
                    formData.append('google_tag', this.form.google_tag);
                    formData.append('georeferencing_type_id', this.form.georeferencing_type_id);
                    formData.append('region_id', this.form.region_id);
                    formData.append('commune_id', this.form.commune_id);
                    formData.append('direct_content_question_id', this.form.direct_content_question_id);
                    formData.append('icon_available_id', this.form.icon_available_id);
                    formData.append('open_app_id', this.form.open_app_id);
                    formData.append('open_app_version_id', this.form.open_app_version_id);
                    formData.append('open_app_uri_url', this.form.open_app_uri_url);
                    formData.append('open_app_desktop_url', this.form.open_app_desktop_url);
                    formData.append('open_app_not_installed', this.form.open_app_not_installed);

                    axios.post('/api/section/store?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Sección Creada');
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

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
                    if (this.form.title.length > 36) {
                        this.errors.push('El nombre debe tener menos de 36 caracteres.');
                    }
                    if (this.form.color == '') {
                        this.errors.push('El color es obligatorio.');
                    }
                    if (this.form.fai == '' && this.form.icon_available_id == 1) {
                        this.errors.push('El icono es obligatorio.');
                    } 
                    if (this.form.position == '') {
                        this.errors.push('La posición es obligatoria.');
                    }
                    if (this.form.google_tag == '') {
                        this.errors.push('La etiqueta de Google es obligatoria.');
                    }
                    if (this.form.georeferencing_type_id == '') {
                        this.errors.push('La pregunta de la georeferenciación es obligatoria.');
                    }
                    if (this.form.georeferencing_type_id == 1 && this.form.region_id == null) {
                        this.errors.push('La región es obligatoria.');
                    }
                    if (this.form.direct_content_question_id == '') {
                        this.errors.push('La pregunta es directo a contenido es obligatoria.');
                    }
                    if (this.form.link_question_id == '') {
                        this.errors.push('La pregunta de enlace es obligatoria.');
                    }
                    if (this.form.iframe_question_id == '') {
                        this.errors.push('La pregunta de iframe es obligatoria.');
                    }
                    if (this.form.youtube_question_id == '') {
                        this.errors.push('La pregunta de youtube es obligatoria.');
                    }
                    if (this.form.open_app_id == 1 && this.form.open_app_version_id == '') {
                        this.errors.push('El método es obligatorio.');
                    }
                    if (this.form.open_app_id == 1 && this.form.open_app_uri_url == '') {
                        this.errors.push('La url de la app es obligatoria.');
                    }
                    if (this.form.open_app_id == 1 && this.form.open_app_desktop_url == '') {
                        this.errors.push('La url de escritorio es obligatoria.');
                    }
                    if (this.form.open_app_id == 1 && this.form.open_app_not_installed == '') {
                        this.errors.push('La url de no instalado es obligatoria.');
                    }

                    window.scrollTo(0, 0);

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
                    limit = 36;

                return (limit - char) + " / " + limit + " caracteres disponibles";
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
