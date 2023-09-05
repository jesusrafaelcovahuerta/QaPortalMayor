<template>
    <div>
        <button v-if="deferredPrompt" @onClick="promptInstall">
    Add to home screen
  </button>
        <qrcode-stream @decode="onDecode"></qrcode-stream>
       
        
    </div>
    
</template>

<script>
    import vPagination from 'vue-plain-pagination';
    import moment from 'moment'
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'


    export default {
        created() {
            this.getPosts();
            this.getRol();
            this.storeAudit();

        },
        methods: {
            onSubmit() {
                this.loading = true; //the loading begin
                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.name == '') {
                    this.form.name = null;
                }

                axios.post('/api/alliance/search/'+ this.form.rut +'/'+ this.form.name + '?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            getPosts() {
                this.loading = true;

                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.name == '') {
                    this.form.name = null;
                }

                if(this.form.rut != null 
                || this.form.name != null
                ) {
                    axios.get('/api/alliance/search/'+this.form.rut+'/'+this.form.name+'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.rowsQuantity = response.data.data.total;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                } else {
                    axios.get('/api/alliance?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.rowsQuantity = response.data.data.total;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            },
            formatDate(value) {
                if(value != '' && value != null && value != '0000-00-00') {
                    return moment(value).format('DD-MM-YYYY');
                } else {
                    return '';
                }
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere desactivar el registro?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/alliance/destroy/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.getPosts();
                        this.$awn.success("El registro ha sido desactivado", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'DeleteAlliance - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            activatePost(id, index) {
                if(confirm("¿Realmente usted quiere activar el registro?")) {
                    this.loading = true; //the loading begin
                    axios.get('/api/alliance/activate/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                        this.getPosts();
                        this.$awn.success("El registro ha sido activado", {labels: {success: "Éxito"}});
                    });

                    let formData = new FormData();
                    formData.append('page', 'ActivateAlliance - '+id);
                
                    axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Alliance');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            onDecode (decodedString) {
                alert(decodedString);
            }
        },
        components: { 
            vPagination,
            ClipLoader,
            QrcodeStream,
            QrcodeDropZone,
            QrcodeCapture,
            BeforeInstallPromptEvent 
        },
        data: function() {
            return {
                color: '#0A2787',
                loading: false,
                form: {
                    rol_id: null,
                    name: '',
                    rut: ''
                },
                branch_office_posts: [],
                supervisor_posts: [],
                rol_id: this.rol_id,
                postsSelected: "",
                posts: [],
                currentPage: 1,
                total: 0,
                rowsQuantity: '',
                bootstrapPaginationClasses: {
                    ul: 'pagination',
                    li: 'page-item',
                    liActive: 'active',
                    liDisable: 'disabled',
                    button: 'page-link'  
                },
                paginationAnchorTexts: {
                    first: '',
                    prev: '&laquo;',
                    next: '&raquo;',
                    last: ''
                },
                mobilepaginationAnchorTexts: {
                    first: '',
                    prev: '',
                    next: '',
                    last: ''
                }
            }
        }
    }
</script>
