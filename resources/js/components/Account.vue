<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
            </div>


            <!-- Earnings (Monthly) Card Example -->
            <div class="col-md-6 offset-md-3 text-center pt-lg-5">
                <img src="backend/img/gigant_logo.png" height="100px" id="logo" class="centered-image">
            </div>

        </div>
    </div>
    
</template>

<script>
    export default {
        created() {
            this.storeAudit();
        },
        methods: {
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Inicio del Panel de Control');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
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