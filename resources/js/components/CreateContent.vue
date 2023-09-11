<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Contenido
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información</h6>
                </div>
                <div class="card-body" v-if="rols_permissions[16]">
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
                                    <div class="col-sm-5">
                                        <label for="exampleInputEmail1">Título <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.title" 
                                        maxlength="50"
                                        class="form-control"
                                        placeholder="Ingresa el título"
                                        >
                                        <span>{{charactersLeft}}</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Sección - Categoría <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.category_id"
                                        @change="getRegionsCommunes"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-for="category_post in category_posts" :key="category_post.category_id" :value="category_post.category_id">{{ category_post.section_title }} - {{ category_post.title }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="exampleInputEmail1">Tipo de Contenido <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.type_id"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option :value="1">Video</option>
                                            <option :value="2">Audio</option>
                                            <option :value="3">Texto</option>
                                            <option :value="4">Pdf</option>
                                            <option :value="5">Iframe</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6" v-if="form.type_id == 1 || form.type_id == 2 || form.type_id == 4 || form.type_id == 5">
                                        <label for="exampleInputEmail1">Descripción <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="text" 
                                        v-model="form.description" 
                                        class="form-control"
                                        placeholder="Ingresa el texto"
                                        >
                                    </div>
                                    <div class="col-sm-3" v-if="form.type_id == 1">
                                        <div v-if="form.type_id == 1">
                                            <label for="exampleInputEmail1">Tipo de Video <h6 class="m-0 text-danger float-right">*</h6></label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.video_type_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option :value="1">Vimeo</option>
                                                <option :value="2">Youtube</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" v-if="form.type_id == 1">
                                        <div v-if="form.type_id == 1">
                                            <label for="exampleInputEmail1">ID del Video Vimeo o Youtube <h6 class="m-0 text-danger float-right">*</h6></label>
                                            <input
                                            type="text" 
                                            v-model="form.video_id" 
                                            class="form-control"
                                            placeholder="Ingresa el ID"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-sm-6" v-if="form.type_id == 2">
                                        <div v-if="form.type_id == 2">
                                            <label for="exampleInputEmail1">Src <h6 class="m-0 text-danger float-right">*</h6></label>
                                            <input
                                            type="text" 
                                            v-model="form.src" 
                                            class="form-control"
                                            placeholder="Ingresa el src del audio"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6" v-if="form.type_id == 4">
                                        <label for="exampleInputEmail1">PDF <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input ref="pdf" accept=".pdf" type="file" class="form-control" v-on:change="onFileChangePdf">
                                    </div>
                                    <div class="col-sm-6" v-if="form.type_id == 5">
                                        <div v-if="form.type_id == 5">
                                            <label for="exampleInputEmail1">Url <h6 class="m-0 text-danger float-right">*</h6></label>
                                            <input
                                            type="text" 
                                            v-model="form.iframe" 
                                            class="form-control"
                                            placeholder="Ingresa el src del iframe"
                                            >
                                        </div>
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
                                    
                                    <div class="col-sm-12" v-if="form.type_id == 3">
                                        <label for="exampleInputEmail1">Texto <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <vue-editor v-model="form.description" ref="editor"></vue-editor>
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">¿La sección tiene icono? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.icon_available_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                        <input type="hidden" v-model="form.icon_type_id">
                                    </div>
                                    <div class="col-sm-4" v-if="form.icon_available_id == 1">
                                        <label for="exampleInputEmail1">Fa Icon - <a href="https://fontawesome.com/icons" target= "_blank">Ver iconos</a></label>
                                        <input
                                            type="text" 
                                            v-model="form.fai" 
                                            class="form-control"
                                            placeholder="Ingresa el icono"
                                        >
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="exampleInputEmail1">Fecha de Inicio <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="date" 
                                        v-model="form.start_date" 
                                        class="form-control"
                                        placeholder="Ingresa la fecha de inicio"
                                        >
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="exampleInputEmail1">Fecha de Termino <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                        type="date" 
                                        v-model="form.end_date" 
                                        class="form-control"
                                        placeholder="Ingresa la fecha de termino"
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
                                        <label for="exampleInputEmail1">¿El enlace de compartir de Whatsapp es personalizado? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.whatsapp_type_id"
                                        >
                                            <option :value="1">Si</option>
                                            <option :value="2">No</option>
                                        </select>
                                        <input type="hidden" v-model="form.icon_type_id">
                                    </div>
                                    <div class="col-sm-6" v-if="form.whatsapp_type_id == 1">
                                        <label for="exampleInputEmail1">Enlace de Whatsapp <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <input
                                            type="text" 
                                            v-model="form.whatsapp_description" 
                                            class="form-control"
                                            placeholder="Ingresa la url a compartir. Ej: (https://conectamayor.com)"
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
                                <router-link to="/content" class="btn btn-danger btn-icon-split">
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
    import { VueEditor } from "vue2-editor";
    import InputColorPicker from 'vue-native-color-picker';
    
    export default {
        components: {
            ClipLoader,
            VueEditor,
            "v-input-colorpicker": InputColorPicker
        },
        created() {
            this.getRegions();
            this.getCommunes();
            this.getRols();
            this.storeAudit();
            this.getCategoryList();
        },
        data: function() {
            return {
                rols_permissions: {},
                errors: [],
                color: '#0A2787',
                loading: false,
                category_posts: [],
                region_posts: [],
                commune_posts: [],
                noFile: false,
                noPdf: false,
                form: {
                    georeferencing_type_id: 2,
                    category_id: null,
                    type_id: null,
                    title: '',
                    icon_type_id: 2,
                    video_id: '',
                    google_tag: '',
                    icon: '',
                    fai: '',
                    image: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    src: '',
                    video_type_id: null,
                    iframe: '',
                    color: '#0b2784',
                    icon_available_id: 2,
                    position: '',
                    whatsapp_type_id: 2,
                    whatsapp_description: '',
                    region_id: null,
                    commune_id: null,
                }
            }
        },
        methods: {
            async getRegionsCommunes() {
                try {
                    const response = await axios.get('/api/category_region/' + this.form.category_id + '/edit?api_token='+App.apiToken);

                    this.stored_regions = response.data.data;

                    console.log(this.stored_regions)

                    this.loading = false;

                    const selectedRegionIds = this.stored_regions.map(item => item.region_id);
                    this.form.region_id = selectedRegionIds;
                } catch (error) {
                    console.error(error);
                }

                try {
                    const response = await axios.get('/api/category_commune/' + this.form.category_id + '/edit?api_token='+App.apiToken);

                    this.stored_communes = response.data.data;

                    console.log(this.stored_commune)

                    this.loading = false;

                    const selectedCommuneIds = this.stored_communes.map(item => item.commune_id);
                    this.form.commune_id = selectedCommuneIds;

                    console.log(this.form.commune_id);
                } catch (error) {
                    console.error(error);
                }
            },
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
            handleChange() {
                this.form.color = this.color;
            },
            onFileChange(e){
                this.file = e.target.files[0];
                this.noFile = e.target.files.length;
            },
            onFileChangePdf(e){
                this.pdf = e.target.files[0];
                this.noPdf = e.target.files.length;
            },
            getCategoryList() {
                axios.get('/api/category/list?api_token='+App.apiToken)
                .then(response => {
                    this.category_posts = response.data.data;
                });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Crear Contenido');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            onSubmit(e) {
                this.loading = true; //the loading begin
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                if(this.form.category_id != null
                    && this.form.type_id != null
                    && ((this.form.type_id == 1 && this.form.video_id != '' && this.form.video_type_id != null) 
                    || (this.form.type_id == 2 && this.form.src != '')
                    || (this.form.type_id == 3 && this.form.description != '')
                    || (this.form.type_id == 4 && this.pdf != null)
                    || (this.form.type_id == 5 && this.form.iframe != '')
                    )
                    && this.form.google_tag != ''
                    && ((this.form.fai != '' && this.form.icon_available_id == 1) || this.form.icon_available_id == 2)
                    && this.form.start_date != ''
                    && this.form.position != ''
                    && this.color != ''
                    & ((this.form.georeferencing_type_id == 2) || (this.form.georeferencing_type_id == 1 && this.form.region_id != null))
                ) {
                    let formData = new FormData();
                    formData.append('category_id', this.form.category_id);
                    formData.append('type_id', this.form.type_id);
                    formData.append('video_id', this.form.video_id);
                    formData.append('title', this.form.title);
                    formData.append('google_tag', this.form.google_tag);
                    formData.append('icon_type_id', this.form.icon_type_id);
                    formData.append('icon', this.form.fai);
                    formData.append('description', this.form.description);
                    formData.append('color', this.form.color);
                    formData.append('start_date', this.form.start_date);
                    formData.append('end_date', this.form.end_date);
                    formData.append('position', this.form.position);
                    formData.append('src', this.form.src);
                    formData.append('file', this.file);
                    formData.append('pdf', this.pdf);
                    formData.append('iframe', this.form.iframe);
                    formData.append('video_type_id', this.form.video_type_id);
                    formData.append('icon_available_id', this.form.icon_available_id);
                    formData.append('region_id', this.form.region_id);
                    formData.append('commune_id', this.form.commune_id);
                    formData.append('georeferencing_type_id', this.form.georeferencing_type_id);

                    axios.post('/api/content/store?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        let formData = new FormData();
                    formData.append('page', 'Contenido Creado');
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/content');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];

                    if (this.form.title == '') {
                        this.errors.push('El título es obligatorio.');
                    }

                    if (this.form.category_id == null) {
                        this.errors.push('La categoría es obligatoria.');
                    }

                    if (this.form.type_id == null) {
                        this.errors.push('El tipo de contenido es obligatorio.');
                    }

                    if ((this.form.type_id == 1 && this.form.video_id == '' && this.form.video_type_id == null) 
                    || (this.form.type_id == 2 && this.form.src == '')
                    || (this.form.type_id == 3 && this.form.description == '')
                    || (this.form.type_id == 5 && this.form.iframe == '')
                    ) {
                        this.errors.push('Los datos del tipo de contenido son obligatorios.');
                    }

                    if (this.form.description == '') {
                        this.errors.push('La descripción es obligatoria.');
                    }
                    
                    if (this.form.color == '') {
                        this.errors.push('El color es obligatorio.');
                    }

                    if (this.form.position == '') {
                        this.errors.push('La posición es obligatoria.');
                    }
                    
                    if (this.form.google_tag == '') {
                        this.errors.push('Las etiqueta de Google es obligatoria.');
                    }

                    if (this.form.icon_available_id == 1 && this.form.fai == '') {
                        this.errors.push('El icono es obligatorio.');
                    }
                    
                    if (this.form.start_date == '') {
                        this.errors.push('La fecha inicial es obligatoria.');
                    }

                    if (this.form.end_date == '') {
                        this.errors.push('La fecha de termino es obligatoria.');
                    }

                    if (this.form.georeferencing_type_id == 1 && this.form.region_id == null) {
                        this.errors.push('La región es obligatoria.');
                    }

                    if (this.form.whatsapp_type_id == 1 && this.form.whatsapp_description == '') {
                        this.errors.push('La descripción de WhatsApp es obligatoria.');
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
                limit = 50;

                return (limit - char) + " / " + limit + " caracteres disponibles";
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
