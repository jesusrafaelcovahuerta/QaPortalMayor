<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Editar Categoría
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
                <div class="card-body" v-if="rols_permissions[13]">
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
                                        <label for="exampleInputEmail1">Subtitulo </label>
                                        <input
                                        type="text" 
                                        v-model="form.subtitle" 
                                        maxlength="36"
                                        class="form-control"
                                        placeholder="Ingresa el subtitulo"
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
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Alianza <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.alliance_id"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-for="alliance_post in alliance_posts" :key="alliance_post.rut" :value="alliance_post.rut">{{ alliance_post.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="exampleInputEmail1">Sección <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.section_id"
                                        @change="getRegionsCommunes"
                                        >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-for="section_post in section_posts" :key="section_post.section_id" :value="section_post.section_id">{{ section_post.section_title }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.georeferencing_type_id == 1">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Región <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.region_id" multiple
                                        @change="getCommunes"
                                        >
                                            <option :value="1000">Todas las regiones y comunas</option>
                                            <option :selected="isSelectedRegion(region_post.region_id)" v-for="region_post in region_posts" :key="region_post.region_id" :value="region_post.region_id">{{ region_post.region }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">Comuna</label>
                                        <select class="form-control" id="exampleFormControlSelect1" v-model="form.commune_id"  multiple>
                                            <option :value="null" v-if="commune_posts.length == 0">No ha seleccionado una región</option>
                                            <option :selected="isSelectedCommune(commune_post.commune_id)" v-for="commune_post in commune_posts" :key="commune_post.commune_id" :value="commune_post.commune_id">{{ commune_post.commune }}</option>
                                        </select>
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
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿La categoría tiene icono? <h6 class="m-0 text-danger float-right">*</h6></label>
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
                                <div class="form-group row" v-if="form.link_question_id == 2">
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
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="exampleInputEmail1">¿Es destacada la categoría? <h6 class="m-0 text-danger float-right">*</h6></label>
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.highlight_id"
                                        >
                                            <option :value="0">No</option>
                                            <option :value="1">Si</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="form.iframe_question_id == 2">
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
                                <button 
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Actualizar</span>
                                </button>
                                <router-link to="/category" class="btn btn-danger btn-icon-split">
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
            this.getPost();
            this.getRegions();
            this.getCommunes();
            this.getAlliaceList();
            this.getSectionList();
            this.getRol();
            this.storeAudit();
        },
        data: function() {
            return {
                rols_permissions: {},
                errors: [],
                color: "#0f4c81",
                loading: false,
                alliance_posts: [],
                section_posts: [],
                region_posts: [],
                commune_posts: [],
                noFile: false,
                noFile_icon_imagen: false,
                stored_regions: [],
                stored_communes: [],
                form: {
                    georeferencing_type_id: '',
                    alliance_id: null,
                    section_id: null,
                    title: '',
                    color: '',
                    position: '',
                    icon_type_id: 2,
                    google_tag: '',
                    fai: '',
                    highlight_id: 0,
                    iframe_question_id: 2,
                    iframe: '',
                    subtitle: '',
                    link_question_id: 2,
                    icon_available_id: 2,
                    region_id: null,
                    commune_id: null,
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
            isSelectedRegion(regionId) {
                return this.stored_regions.some(item => item.region_id === regionId);
            },
            isSelectedCommune(communeId) {
                return this.stored_communes.some(item => item.commune_id === communeId);
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
            async getRegionsCommunes() {
                try {
                    const response = await axios.get('/api/category_region/' + this.$route.params.id + '/edit?api_token='+App.apiToken);

                    this.stored_regions = response.data.data;

                    this.loading = false;

                    const selectedRegionIds = this.stored_regions.map(item => item.region_id);
                    this.form.region_id = selectedRegionIds;
                } catch (error) {
                    console.error(error);
                }

                try {
                    const response = await axios.get('/api/category_commune/' + this.$route.params.id + '/edit?api_token='+App.apiToken);

                    this.stored_communes = response.data.data;

                    this.loading = false;

                    const selectedCommuneIds = this.stored_communes.map(item => item.commune_id);
                    this.form.commune_id = selectedCommuneIds;

                    console.log(this.form.commune_id);
                } catch (error) {
                    console.error(error);
                }
            },
            async getPost() {
                try {
                    const response = await axios.get('/api/category/' + this.$route.params.id + '/edit?api_token='+App.apiToken);

                    this.post = response.data.data;

                    this.$set(this.form, 'georeferencing_type_id', this.post.georeferencing_type_id);
                    this.$set(this.form, 'alliance_id', this.post.alliance_id);
                    this.$set(this.form, 'section_id', this.post.section_id);
                    this.$set(this.form, 'title', this.post.title);
                    this.$set(this.form, 'color', this.post.color);
                    this.$set(this.form, 'position', this.post.position);
                    this.$set(this.form, 'icon_type_id', this.post.icon_type_id);
                    this.$set(this.form, 'iframe', this.post.iframe);

                    if (this.post.iframe != null && this.post.iframe != '') {
                        this.$set(this.form, 'iframe_question_id', 1);
                    } else {
                        this.$set(this.form, 'iframe_question_id', 2);
                    }


                    this.$set(this.form, 'subtitle', this.post.subtitle);
                    this.$set(this.form, 'link_question_id', this.post.link_question_id);
                    this.$set(this.form, 'icon_available_id', this.post.icon_available_id);
                    this.$set(this.form, 'url', this.post.url);

                    if (this.post.icon != null) {
                        var icon = this.post.icon.split(' ');

                        icon = icon[0] +' '+ icon[1];
                    }

                    this.$set(this.form, 'fai', icon);
                    this.$set(this.form, 'highlight_id', this.post.highlight_id);

                    var google_tag = this.post.google_tag.split('_');

                    this.$set(this.form, 'google_tag', google_tag[1]);
                    this.$set(this.form, 'region_id', this.post.region_id);
                    this.$set(this.form, 'commune_id', this.post.commune_id);

                    this.loading = false;
                } catch (error) {
                    console.error(error);
                }

                try {
                    const response = await axios.get('/api/category_region/' + this.$route.params.id + '/edit?api_token='+App.apiToken);

                    this.stored_regions = response.data.data;

                    this.loading = false;

                    const selectedRegionIds = this.stored_regions.map(item => item.region_id);
                    this.form.region_id = selectedRegionIds;
                } catch (error) {
                    console.error(error);
                }

                try {
                    const response = await axios.get('/api/category_commune/' + this.$route.params.id + '/edit?api_token='+App.apiToken);

                    this.stored_communes = response.data.data;

                    this.loading = false;

                    const selectedCommuneIds = this.stored_communes.map(item => item.commune_id);
                    this.form.commune_id = selectedCommuneIds;

                    console.log(this.form.commune_id);
                } catch (error) {
                    console.error(error);
                }
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Editar Categoría - Id de la Categoría: '+this.$route.params.id);
               
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
            onIconChange(e){
                this.icon_image = e.target.files[0];
                this.noFile_icon_imagen = e.target.files.length;
            },
            getAlliaceList() {
                axios.get('/api/alliance/list?api_token='+App.apiToken)
                .then(response => {
                    this.alliance_posts = response.data.data;
                });
            },
            getSectionList() {
                axios.get('/api/section/list?api_token='+App.apiToken)
                .then(response => {
                    this.section_posts = response.data.data;
                });
            },
            onSubmit(e) {
                this.loading = true; //the loading begin
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                if(this.form.alliance_id != null
                    && this.form.section_id != null
                    && this.form.title != ''
                    && this.form.google_tag != ''
                    && this.form.title.length <= 28
                    && this.form.color != ''
                    && this.form.position != ''
                    && this.form.region_id != null
                    && ((this.form.georeferencing_type_id == 2) || (this.form.georeferencing_type_id == 1 && this.form.region_id != null))
                    && (this.form.icon_available_id == 2 || (this.form.icon_available_id == 1 && this.form.fai != ''))
                ) {
                    let formData = new FormData();
                    formData.append('alliance_id', this.form.alliance_id);
                    formData.append('section_id', this.form.section_id);
                    formData.append('title', this.form.title);
                    formData.append('color', this.form.color);
                    formData.append('position', this.form.position);
                    formData.append('icon_type_id', this.form.icon_type_id);
                    formData.append('iframe', this.form.iframe);
                    formData.append('iframe_question_id', this.form.iframe_question_id);
                    formData.append('subtitle', this.form.subtitle);
                    formData.append('link_question_id', this.form.link_question_id);
                    formData.append('icon_available_id', this.form.icon_available_id);
                    formData.append('url', this.form.url);
                    formData.append('icon', this.form.fai);
                    formData.append('highlight_id', this.form.highlight_id);
                    formData.append('google_tag', this.form.google_tag);
                    formData.append('region_id', this.form.region_id);
                    formData.append('commune_id', this.form.commune_id);
                    formData.append('georeferencing_type_id', this.form.georeferencing_type_id);

                    axios.post('/api/category/update/'+ this.$route.params.id +'?api_token='+App.apiToken, formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        let formData = new FormData();
                        formData.append('page', 'Categoría Actualizada - Id de la Categoría: '+this.$route.params.id);
                    
                        axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                        .then(function (response) {
                            currentObj.success = response.data.success;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                        
                        this.loading = false;
                        this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
                        this.$router.push('/category');
                    });
                } else {
                    this.loading = false;
                    this.errors = [];
                    if (this.form.title == '') {
                        this.errors.push('El título es obligatorio.');
                    }
                    if (this.form.alliance_id == null) {
                        this.errors.push('La alianza es obligatoria.');
                    }
                    if (this.form.section_id == null) {
                        this.errors.push('La sección es obligatoria.');
                    }
                    if (this.form.color == '') {
                        this.errors.push('El color es obligatorio.');
                    }
                    if (this.form.position == '') {
                        this.errors.push('La posición es obligatoria.');
                    }
                    if (this.form.google_tag == '') {
                        this.errors.push('La etiqueta de Google es obligatoria.');
                    }
                    if (this.form.title.length > 28) {
                        this.errors.push('El nombre debe tener menos de 28 caracteres.');
                    }
                    if (this.form.icon_available_id == 1 && this.form.fai == '') {
                        this.errors.push('El icono es obligatorio.');
                    }

                    window.scrollTo(0, 0);

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
