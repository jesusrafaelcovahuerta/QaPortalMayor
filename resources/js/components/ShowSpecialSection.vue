<template>
    <div class="container pt-32">
        <div class="container pt-32" v-if="this.$route.params.id == 67">
            <h1><center>Si necesitas leer algún Qr puede utilizar esta sección para hacerlo, solo debe colocar la camara sobre el mismo</center></h1>
            <hr>
            <qrcode-stream @decode="onDecode"></qrcode-stream>

           
        </div>
        <div class="container pt-32" v-if="this.$route.params.id == 68">
            <h1><center>Noticias</center></h1>
            <hr>
            <div v-for="(post, index) in posts" v-bind:index="index">
                <h4>{{ post.title }} - <router-link :to="`/information/show/${post.datum_id}`">Toca aquí para leer la noticia</router-link></h4>
                <hr>
            </div>

            <v-pagination v-model="currentPage" 
                            :page-count="total"
                            @input='getPosts'
                            :classes="bootstrapPaginationClasses"
                            :labels="paginationAnchorTexts"
                            ></v-pagination>
        </div>
        <div class="container pt-32" v-if="this.$route.params.id == 70">
            <h1><center>Beneficios del Estado</center></h1>
            <hr>
            <button class="boton2" :style="{ background: '#0e385d'}" v-on:click="goWeb('https://www.reddeproteccion.cl/', 'Red de Protección')"> 
                <i v-bind:class="'fa-solid fa-circle-info'"></i><br> Red de Protección
            </button>
            <button class="boton2" :style="{ background: '#0e385d'}" v-on:click="goWeb('https://persona.ipsenlinea.cl/IpsEnLinea/web/ingresoPersonas.jsf', 'IPS En Línea')"> 
                <i v-bind:class="'fa-solid fa-hand-point-up'"></i><br> IPS En Línea
            </button>
        </div>
    </div>
	
</template>
<script>
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'
    import vPagination from 'vue-plain-pagination';
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        created() {
            this.getPosts();
        },
        methods: {
            Track(google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });
            },
            goWeb(url, google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });

                window.open(url, '_blank');
            },
            getPosts() {
                this.loading = true;

                axios.get('/api/information?page='+this.currentPage)
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
            },
            onDecode (decodedString) {
                window.open(
                    decodedString,
                    '_blank'
                );
            },
            goHome() {
                this.$router.push('/')
            },
            goBack() {
                this.$router.go(-1)
            }
        },
        components: {
            QrcodeStream,
            QrcodeDropZone,
            QrcodeCapture,
            BeforeInstallPromptEvent,
            vPagination,
            ClipLoader
        },
        data: function() {
            return {
                posts: [],
                post: '',
                polls: [],
                poll_question_posts: [],
                poll_quantity: 0,
                check_category_poll: '',
                form: {
                    yes_no_answer: [],
                    text_answer: []
                },
                color: '#0A2787',
                loading: false,
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
<style scoped>
.boton2 {
  font-size: 25px !important;
}
</style>