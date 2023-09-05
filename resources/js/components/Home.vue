<template>
    <div class="container pt-32">

        <div class="row">
            <hr>
            <h2><center><strong>{{ title }}</strong></center></h2>
        </div>
        <div class="row">
		    <div class="col-6" v-for="(post, index) in posts" v-bind:index="index">
                <div v-if="post.special_section_id == 1">
                    <div v-if="post.section_id == 46">
                        <button v-if="post.link_question_id == 1" class="boton2" :style="{ background: post.color}" v-on:click="goWeb('http://api.whatsapp.com/send/?phone=56934136117&text=Hola&type=phone_number&app_absent=0',post.google_tag)" >
                            <font class="title">Consulta tu saldo</font><br> <center><img style="width: 40px; height: 40px;" :src="'/../frontend/images/logo_entel.png'"></center>
                        </button>
                    </div>
                    <div v-if="post.section_id == 67">
                        <router-link @click.native="Track(post.google_tag)" class="boton2" :style="{ background: post.color}" :to="`/special_section/show/67`" >
                            <i v-bind:class="'fa-solid fa-qrcode'"></i><br><font class="title">Leer Qr</font>
                        </router-link>
                    </div>
                    <div v-if="post.section_id == 68">
                        <router-link @click.native="Track(post.google_tag)" class="boton2" :style="{ background: post.color}" :to="`/special_section/show/68`" >
                            <i v-bind:class="'fa-solid fa-newspaper'"></i><br><font class="title">Noticias</font>
                        </router-link>
                    </div>
                    <div v-if="post.section_id == 4">
                        <a class="boton2" :style="{ background: post.color}" href="tel:226043712" target="_blank">
                            <center><img id="salcobrand_logo" :src="'/../frontend/images/salcobrand-seeklogo.com.png'" alt=""></center><font class="title">Salcobrand</font>
                        </a>
                    </div>
                    <div v-if="post.section_id == 70">
                        <router-link @click.native="Track(post.google_tag)" class="boton2" :style="{ background: post.color}" :to="`/special_section/show/70`" >
                            <i v-bind:class="'fa-solid fa-handshake'"></i><br><font class="title">Beneficios del Estado</font>
                        </router-link>
                    </div>
                </div>
                <div v-else>
                    <div v-if="post.icon_available_id == 1">
                        <div v-if="post.link_question_id == 2">
                            <div v-if="post.open_app_id == 2">
                                <router-link @click.native="Track(post.google_tag)" v-if="post.direct_content_question_id == 2 || post.direct_content_question_id == null" class="boton2" :style="{ background: post.color}" :to="`/section/show/${post.section_id}`" >
                                    <i v-bind:class="post.icon"></i><br><font class="title">{{ post.section_title }}</font>
                                </router-link>
                                <router-link @click.native="Track(post.google_tag)" v-if="post.direct_content_question_id == 1" class="boton2" :style="{ background: post.color}" :to="`/section/content/show/${post.section_id}`" >
                                    <i v-bind:class="post.icon"></i><br><font class="title">{{ post.section_title }}</font>
                                </router-link>
                            </div>
                            <div v-else>
                                <button class="boton2 app" :style="{ background: post.color}" v-on:click="openApp(post.open_app_uri_url, post.google_tag, post.open_app_desktop_url, post.open_app_not_installed)" >
                                    <i v-bind:class="post.icon"></i><br><font class="title">{{ post.section_title }}</font> 
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <button class="boton2" :style="{ background: post.color}" v-on:click="goWeb(post.url, post.google_tag)" >
                                <i v-bind:class="post.icon"></i><br> <font class="title">{{ post.section_title }}</font>
                            </button>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="post.link_question_id == 2">
                            <div v-if="post.open_app_id == 2">
                                <router-link @click.native="Track(post.google_tag)" v-if="post.direct_content_question_id == 2 || post.direct_content_question_id == null" class="boton2 link" :style="{ background: post.color}" :to="`/section/show/${post.section_id}`" >
                                    <br><font class="title">{{ post.section_title }}</font>
                                </router-link>
                                <router-link @click.native="Track(post.google_tag)" v-if="post.direct_content_question_id == 1" class="boton2 link" :style="{ background: post.color}" :to="`/section/content/show/${post.section_id}`" >
                                    <br><font class="title">{{ post.section_title }}</font>
                                </router-link>
                            </div>
                            <div v-else>
                                <button class="boton2 app" :style="{ background: post.color}" v-on:click="openApp(post.open_app_uri_url, post.google_tag, post.open_app_desktop_url, post.open_app_not_installed)" >
                                    <font class="title">{{ post.section_title }}</font> 
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <button class="boton2" :style="{ background: post.color}" v-on:click="goWeb(post.url, post.google_tag)" >
                                <font class="title">{{ post.section_title }}</font>
                            </button>
                        </div>
                    </div>
                    
                </div>
		    </div>
        </div>
        <b-modal ref="my-modal" hide-footer title="Nueva Noticia">
            <div class="d-block text-center">
                <img style="width: 100%;" :src="'/../frontend/images/mesa.jpg'" alt="">
            </div>
        </b-modal>
    </div>
</template>
<script>
    export default {
        created() {
            this.getRegion();
            this.getCommune();
            this.getPosts();
            this.checkDate();
        },
        mounted() {
            this.detectDispositive();
            this.title = App.appTitle;
        },
        methods: {
            detectDispositive() {
                const userAgent = window.navigator.userAgent.toLowerCase();
                
                if (/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i.test(userAgent)) {
                    this.dispositive = "phone";
                } else {
                    this.dispositive = "desktop";
                }
            },
            hideModal() {
                this.$refs['my-modal'].hide()
            },
            toggleModal() {
                // We pass the ID of the button that we want to return focus to
                // when the modal has hidden
                this.$refs['my-modal'].toggle('#toggle-btn')
            },
            handleBlur(e) {
                this.$set(this.form, 'search', '');

                this.$gtag.event('page_view', {
                    page_title: 'Buscador de Google en Home'
                });
            },
            Track(google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });
            },
            goPhone(number, google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });

                window.location.href = 'tel:' + number;
            },
            hideModal() {
                this.modalShow = false;
            },
            goWeb(url, google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });

                window.location.href = url;
            },
            openApp(uri_url, google_tag, open_app_desktop_url, open_app_not_installed) {
                try {
                    this.$gtag.event('page_view', {
                        page_title: google_tag
                    });

                    if (this.dispositive == 'desktop') {
                        window.location.href = open_app_desktop_url;
                    } else {
                        window.location.href = uri_url;
                        setTimeout(function(){
                            if(confirm('No tienes instalado la App o falta una actualización. ¿Deseas ir a Google Play?')){
                                window.location.href = open_app_not_installed;
                            }
                        }, 300);
                    }
                } catch (error) {
                    console.error('An error occurred:', error);
                }
            },
            checkDate() {
                let formData = new FormData();
                formData.append('page', 'Home');
               
                axios.post('/api/content/date', formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            async getRegion() {
                try {
                    const response = await axios.post('/api/region/find');

                    this.region = response.data.data.region_id;
                } catch (error) {
                    console.log(error);
                }
            },
            async getCommune() {
                try {
                    const response = await axios.post('/api/commune/find');

                    this.commune = response.data.data.commune_id;
                } catch (error) {
                    console.log(error);
                }
            },
            async getPosts() {
                try {
                    await this.getRegion(); // Espera a que se complete getRegion()

                    this.loading = true;

                    let formData = new FormData();
                    formData.append('region', this.region);
                    formData.append('commune', this.commune);

                    if (this.region == null && this.commune == null) {
                        this.posts = '';
                    } else {
                        const response = await axios.post('/api/section/home', formData);
                        this.posts = response.data.data;
                    }
                } catch (error) {
                    console.log(error);
                } finally {
                    this.loading = false;
                }
            }
        },
        data: function() {
            return {
                region: '',
                commune: '',
                modalShow: '',
                posts: [],
                form: {
                    search: 'Buscar en Google.com'
                },
                dispositive: '',
                title: '',
            }
        }
    }
</script>
<style scoped>
.alert-secondary {
  color: #FFF !important;
  background-color: rgb(47, 47, 98);
  text-align: center !important;
  font-weight: bold;
}
/* || PRIMARY MENU || */

* {
  box-sizing: border-box;
}

/* Style the search field */
form.example input[type=text] {
  padding: 10px;
  font-size: 17px;
  border: 1px solid grey;
  float: left;
  width: 80%;
  background: #fff;
  color: #000 !important;
}

/* Style the submit button */
form.example button {
  float: left;
  width: 20%;
  padding: 10px;
  background: #2196F3;
  color: white;
  font-size: 17px;
  border: 1px solid grey;
  border-left: none; /* Prevent double borders */
  cursor: pointer;
}

form.example button:hover {
  background: #0b7dda;
}

/* Clear floats */
form.example::after {
  content: "";
  clear: both;
  display: table;
}

::-webkit-input-placeholder { /* WebKit, Blink, Edge */
    color:    black;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
   color:    black;
   opacity:  1;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
   color:    black;
   opacity:  1;
}
:-ms-input-placeholder { /* Internet Explorer 10-11 */
   color:    black;
}
::-ms-input-placeholder { /* Microsoft Edge */
   color:    black;
}
::placeholder { /* Most modern browsers support this now. */
   color:    black;
}
</style>