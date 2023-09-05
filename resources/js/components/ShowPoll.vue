<template>
    <div class="container pt-32">
        <div class="row">
            <div>
                <div class="col-12" v-for="(post, index) in poll_question_posts" v-bind:index="index">
                    <form @submit.prevent="onSubmit" ref="createCollection" enctype="multipart/form-data">
                        <h2>{{ post.question }}</h2>
                        <hr>
                        <div class="form-group" v-if="post.answer_type_id == 1">
                            <label class="question_poll_yes_no" style="font-size: 20px;" for="yes">Si</label>   <input style="font-size: 30px !important;" type="radio" sty v-model="form.yes_no_answer[index]" id="yes_no_asnwer" value="Si" required>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      
                            <label class="question_poll_yes_no" style="font-size: 20px;" for="no">No</label>    <input type="radio" v-model="form.yes_no_answer[index]" id="yes_no_asnwer" value="No" required>

                        </div>
                        <div class="form-group" v-if="post.answer_type_id == 2">
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
                    </form>
                </div>
            </div>
            
        </div>
    </div>
	
</template>
<script>
    export default {
        created() {
            this.getPosts();
            this.getPollQuestions();
            this.checkDate();
        },
        methods: {
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

                axios.post('/api/poll/specialanswer', formData, config)
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
            getPollQuestions() {
                axios.get('/api/poll/detail/'+ this.$route.params.id)
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
                formData.append('page', 'section_id_'+this.$route.params.id);
               
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
            getPosts() {
                this.loading = true;

                axios.get('/api/category/show/'+ this.$route.params.id)
                .then(response => {
                    this.posts = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        },
        data: function() {
            return {
                posts: [],
                polls: [],
                poll_question_posts: [],
                poll_quantity: 0,
                form: {
                    yes_no_answer: [],
                    text_answer: []
                },
            }
        }
    }
</script>