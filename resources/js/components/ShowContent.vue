<template>
    <div class="container pt-32">
        <div v-if="poll_question_posts == ''" class="row">
            <h1><center>{{ this.post.title }}</center></h1>
            <hr>
                
            <a :href="`whatsapp://send?text=${encodeURIComponent(whatsapp_description)}`" style="whatsapp_share_button" data-action="share/whatsapp/share"> <strong>Compartir por Whatsapp</strong> <i class="fab fa-whatsapp"></i></a>
                
		    <div class="col-12" v-if="this.post.type_id == 1 || this.post.type_id == 2 || this.post.type_id == 4 || this.post.type_id == 5">

                <div v-if="url !== null">
                    <center>
                        <img v-bind:src="image_url">
                        <br>
                        <audio controls autoplay>
                            <source :src="url" type="audio/mpeg">
                        </audio>
                    </center>
                </div>
                
                <div v-if="videoID !== null">
                    <div v-if="this.post.video_type_id == 1">
                        <vimeo-player
                            class="vimeo"
                            ref="player"
                            :options="{ responsive: true, autoplay: true }"
                            :video-id="videoID"
                            @ready="onReady"
                            :autoplay="true"
                            :player-height="320"
                            :player-width="640"
                        ></vimeo-player>
                    </div>
                    <div v-if="this.post.video_type_id == 2">
                        <iframe width="100%" height="" :src="`https://www.youtube.com/embed/${videoID}?autoplay=1`" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>

                <div v-if="this.post.type_id == 4">
                    <iframe :src="`https://docs.google.com/viewer?url=${pdf_url}&embedded=true`" width="100%" height="780" style="border: none;"></iframe>
                </div>
                <div v-if="this.post.type_id == 5">
                    <center>
                        <iframe width="100%" height="600" :src="`${post.iframe}`" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </center>
                </div>
                <hr>	
                <h3>{{ this.post.description }}</h3>	    
            </div>
            <div class="col-12 pt-10" v-if="this.post.type_id == 3" v-html="this.post.description">	    
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
                    <router-link  class="pollboton" :style="{ background: '#572364'}" :to="`/poll/show/${post.poll_id}`"> 
                        <font class="title">{{ post.title }}</font>
                    </router-link>
                </div>
            </div>
        </div>
       

    </div>
	
</template>
<script>
    import pdf from 'vue-pdf'

    var loadingTask;

    export default {
        created() {
            this.getPollQuestions();
            this.getPollQuantity();
            this.getPolls();
            this.getPost();
        },
        components: {
            pdf
        },
        methods: {
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
            onReady() {
                this.playerReady = true
            },
            play () {
                this.$refs.player.play()
            },
            pause () {
                this.$refs.player.pause()
            },
            catchUser() {
                let formData = new FormData();
                formData.append('page', 'content_id_'+this.$route.params.id);
               
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

                axios.get('/api/content/detail/'+ this.$route.params.id)
                .then(response => {
                    this.post = response.data.data;
                    this.url = this.post.src;
                    this.image_url = '/public/files/'+this.post.image;

                    var fullUrl = window.location.href;
                    var splitted_url = fullUrl.split('/');
                    var new_url = splitted_url[0] + '//' + splitted_url[2];

                    this.pdf_url = new_url + '/public/files/'+this.post.pdf;
                    this.videoID = this.post.video_id;
                    
                    if(this.post.whatsapp_description == null || this.post.whatsapp_description == '') {
                        this.whatsapp_description = 'Hola te quiero compartir un enlace de la Fundación Conecta Mayor - ' + window.location.href;
                    } else {
                        this.whatsapp_description = 'Hola te quiero compartir un enlace de la Fundación Conecta Mayor - ' + this.post.whatsapp_description;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        },
        mounted() {

		this.src.promise.then(pdf => {

			this.numPages = pdf.numPages;
		});
	},
        data: function() {
            return {
                src: loadingTask,
			    numPages: undefined,
                polls: [],
                poll_question_posts: [],
                poll_quantity: 0,
                posts: [],
                videoID: null,
                height: 500,
                post: '',
                url: null,
                whatsapp_description: '',
                options: {
                    muted: true,
                    autoplay: true,
			    },
			    playerReady: false,
                form: {
                    yes_no_answer: [],
                    text_answer: []
                }
            }
        }
    }
</script>

<style scoped>    
    .fa-whatsapp {
        color: #25D366;
        font-size: 30px !important;
    }

    .whatsapp_share_button {
        font-size: 18px !important;
    }
</style>