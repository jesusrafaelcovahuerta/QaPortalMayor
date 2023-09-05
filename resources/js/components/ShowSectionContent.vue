<template>
    <div class="container pt-32">
        <div v-if="loading">
            <center>
                <clip-loader :color="color"></clip-loader>
            </center>
        </div>
        <div v-else>
            <div class="row" v-if="post.section_subtitle != ''">
                <hr>
                <h2><center><strong>{{ post.section_subtitle }}</strong></center></h2>
            </div>

            <div class="col-12" v-for="(post, index) in section_content_posts" v-bind:index="index">
                <router-link class="boton2" :style="{ background: '#0e385d'}" :to="`/content/show/${post.content_id}`"> 
                    <i v-bind:class="post.icon"></i><br> <font class="title">{{ post.title }}</font> 
                </router-link>
            </div>
        </div>

    </div>
	
</template>
<script>
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    export default {
        components: {
            ClipLoader
        },
        created() {
            this.getSection();
            this.getSectionContentPosts();
        },
        methods: {
            Track(google_tag) {
                this.$gtag.event('page_view', {
                    page_title: google_tag
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
            getSection() {
                axios.get('/api/section/'+ this.$route.params.id+'/edit')
                .then(response => {
                    this.post = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getSectionContentPosts() {
                this.loading = true;

                axios.get('/api/section_content/show/'+ this.$route.params.id)
                .then(response => {
                    this.section_content_posts = response.data.data;
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
                post: '',
                loading: false
            }
        }
    }
</script>
<style scoped>
.boton2 {
  font-size: 25px !important;
}
</style>