<template>
    <div class="container pt-32">
        <div class="row">
		    <div class="col-6" v-for="(post, index) in posts" v-bind:index="index">
                <a class="boton2" :style="{ background: post.color}"> 
                    <i v-bind:class="post.icon"></i><br> {{ post.section_title }}
                </a>
		    </div>
        </div>

    </div>
</template>
<script>
    export default {
        created() {
            this.getPosts();
        },
        methods: {
            getPosts() {
                this.loading = true;

                axios.get('/api/section')
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
        data: function() {
            return {
                posts: []
            }
        }
    }
</script>