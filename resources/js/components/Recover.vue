<template>
     <div class="flex flex-wrap w-full justify-center items-center pt-56">
        <div class="flex flex-wrap max-w-xl">
            <div class="p-2 text-2xl text-gray-800 font-semibold"><h1>¿Olvidastes la contraseña?</h1></div>
            <div class="p-2 w-full">
                <label for="email">¿Cuál es tu RUT?</label>
                <input class="w-full bg-gray-100 rounded border border-gray-400 focus:outline-none focus:border-indigo-500 text-base px-4 py-2" placeholder="RUT" type="email" v-model="form.email">
            </div>
            <div class="p-2 w-full mt-4">
                <button @click.prevent="loginUser" type="submit" class="flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Ingresar</button>
            </div>
        </div> 
    </div>
</template>
<script>
export default {
    data(){
        return{
            form:{
                email: ''
            },
            errors: []
        }
    },
    methods:{
         loginUser(){
             axios.post('/api/login', this.form).then(() =>{
                this.$router.push('/account');
             }).catch((error) =>{
                this.errors = error.response.data.errors;
            })
         }
    }
}
</script>