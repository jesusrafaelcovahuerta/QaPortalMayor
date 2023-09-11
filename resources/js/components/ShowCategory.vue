<template>
    <div class="container pt-32">
        <div class="row" v-if="post.subtitle != ''">
            <h2><center><strong>{{ post.subtitle }}</strong></center></h2>
        </div>
        <div class="row" v-if="post.description != '' && post.description != null">
            <hr>
            <h3><center><strong>Descripción: {{ post.description }}</strong></center></h3>
        </div>
        <div v-if="post.iframe != null" class="row">
            <h1><center>{{ post.section_title }}</center></h1>
            <iframe width="600" height="600" :src="`${post.iframe}`" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div v-else>
            <div v-if="check_category_poll == 0">
                <div v-if="poll_question_posts == ''" class="row">
                    <div class="col-8 col-8-scroll" ref="col8">
                        <div class="p-0">
                            <div class="col-12" v-for="(post, index) in posts" v-bind:index="index">
                                <router-link v-if="post.icon_available_id == '1'" @click.native="Track(post.google_tag)" class="boton2" :style="{ background: post.color}" :to="`/content/show/${post.content_id}`"> 
                                    <i v-bind:class="post.icon"></i><br> <font class="title">{{ post.title }}</font>
                                </router-link>
                                <router-link v-else @click.native="Track(post.google_tag)" class="boton2 link" :style="{ background: post.color}" :to="`/content/show/${post.content_id}`"> 
                                    <br><font class="title">{{ post.title }}</font>
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="circle" @click="scrollContent(-50)">
                                <i class="fas fa-chevron-up"></i>
                            </div>
                            <div class="circle" @click="scrollContent(50)">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="poll_question_posts != ''" class="row">
                    <div v-if="poll_quantity == 1">
                        <form @submit.prevent="onSubmit" ref="createCollection" enctype="multipart/form-data">
                            <div class="col-12" v-for="(post, index) in poll_question_posts" v-bind:index="index">
                            
                                <h2>{{ post.question }}</h2>
                                <hr>
                                <div class="form-group" v-if="post.answer_type_id == 1">
                                    <h4>Selecciona la respuesta marcando en el circulo</h4>
                                    <hr>
                                    <label class="question_poll_yes_no" style="font-size: 20px;" for="yes">Si</label>   <input style="font-size: 30px !important;" type="radio" sty v-model="form.yes_no_answer[index]" id="yes_no_asnwer" value="Si" required>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      
                                    <label class="question_poll_yes_no" style="font-size: 20px;" for="no">No</label>    <input type="radio" v-model="form.yes_no_answer[index]" id="yes_no_asnwer" value="No" required>

                                </div>
                                <div class="form-group" v-if="post.answer_type_id == 2">
                                    <h4>Escriba su respuesta</h4>
                                    <hr>
                                    <input
                                        type="text" 
                                        v-model="form.text_answer[index]" 
                                        class="form-control"
                                        placeholder="Ingresa la respuesta"
                                        required
                                    >
                                </div>

                                <button
                                    type="submit" class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Guardar</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div v-if="poll_quantity > 1">
                        <div class="col-md-12" v-for="(post, index) in polls" v-bind:index="index">
                            <router-link  class="pollboton link" :style="{ background: post.color}" :to="`/poll/show/${post.poll_id}`"> 
                                <br><font class="title">{{ post.title }}</font>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        created() {
            this.getRegion();
            this.getCommune();
            this.checkContentPoll();
            this.getPollQuestions();
            this.getPollQuantity();
            this.getPost();
            this.getPosts();
            this.checkDate();
            this.getPolls();
        },
        methods: {
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
            scrollContent(offset) {
                const col8 = this.$refs.col8;
                col8.scrollTop += offset;
            },
            Track(google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });
            },
            onSubmit(e) {
                this.loading = true;
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
            
                formData.append('poll_id', this.$route.params.id);
                formData.append('yes_no_answers', this.form.yes_no_answer);

                formData.append('text_answers', this.form.text_answer);

                axios.post('/api/poll/answer', formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                    this.$awn.success("La encuesta han sido contestada", {labels: {success: "Éxito"}});
                    this.$router.push('/');
                });
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
            getPollQuantity() {
                axios.get('/api/poll/quantity/'+ this.$route.params.id)
                .then(response => {
                    this.poll_quantity = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            checkContentPoll() {
                axios.get('/api/category/poll/'+ this.$route.params.id)
                .then(response => {
                    this.check_category_poll = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            getPolls() {
                axios.get('/api/poll/all/'+ this.$route.params.id)
                .then(response => {
                    this.polls = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            getPollQuestions() {
                axios.get('/api/poll/show/'+ this.$route.params.id)
                .then(response => {
                    this.poll_question_posts = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            catchUser() {
                let formData = new FormData();
                formData.append('page', 'category_id_'+this.$route.params.id);
               
                axios.post('/api/user/catch', formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            goHome() {
                this.$router.push('/')
            },
            goBack() {
                this.$router.go(-1)
            },
            getPost() {
                this.loading = true;

                axios.get('/api/category/'+ this.$route.params.id +'/edit')
                .then(response => {
                    this.post = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            async getPosts() {
                try {
                    await this.getRegion(); // Espera a que se complete getRegion()

                    this.loading = true;

                    let formData = new FormData();
                    formData.append('category_id',this.$route.params.id);
                    formData.append('region', this.region);
                    formData.append('commune', this.commune);

                    if (this.region == null && this.commune == null) {
                        this.posts = '';
                    } else {
                        const response = await axios.post('/api/content/show', formData);
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
                polls: [],
                poll_question_posts: [],
                poll_quantity: 0,
                check_category_poll: '',
                posts: [],
                post: '',
                form: {
                    yes_no_answer: [],
                    text_answer: []
                }
            }
        }
    }
</script>
<style scoped>
    .boton2 {
    font-size: 25px !important;
    }

    .circle-container {
        position: absolute;
        top: 50%; /* Alinea el contenedor al centro vertical */
        left: 50%; /* Ajusta la posición horizontal según tus necesidades */
        transform: translate(-50%, -50%); /* Centra el contenedor en la pantalla */
    }

    .circle {
        
        width: 60px;
        height: 60px;
        background-color: #3498db;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px;
        color: white;
        font-size: 24px;
        cursor: pointer;
        text-shadow: 0px 0px 5px #fff, 0px 0px 5px #fff;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        transition: top 0.3s ease;
    }

    .circle i {
    display: block;
    }

    .circle:hover {
    background-color: #2980b9;
    }

    /* Agrega estilos para la altura máxima y oculta el scrollbar en la columna de ancho 8 */
    .col-8-scroll {
        max-height: 560px; /* Ajusta la altura máxima según tus necesidades */
        overflow-y: scroll;
        scrollbar-width: none; /* Oculta el scrollbar en navegadores Firefox */
        -ms-overflow-style: none; /* Oculta el scrollbar en navegadores Edge */
    }
    
    /* Estilos para ocultar el scrollbar en navegadores Chrome, Safari, y nuevos navegadores Edge */
    .col-8-scroll::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    body {
        overflow: hidden;
    }
</style>