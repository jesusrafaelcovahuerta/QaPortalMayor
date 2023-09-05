<template>
    <div class="container pt-32">
        <h1 style="font-size: 30px !important;"><center><strong>{{ post.title }}</strong></center></h1>
        <hr>
        <p style="text-align: justify; font-size: 30px !important;" v-html="post.description">
        
        </p>
        <strong>Fuente:</strong> {{ post.url }}
    </div>
	
</template>
<script>
    export default {
        created() {
            this.getPost();
        },
        methods: {
            Track(google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
                });
            },
            getPost() {
                axios.get('/api/information/show/'+ this.$route.params.id)
                .then(response => {
                    this.post = response.data.data;

                    this.$set(this.form, 'title', this.post.title);
                    this.$set(this.form, 'description', this.post.description);
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
            }
        }
    }
</script>
<style scoped>
.boton2 {
  font-size: 25px !important;
}
</style>