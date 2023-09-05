<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Resultado de Metricas
            </h1>
            <hr>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Páginas más visitadas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <GChart
                            type="ColumnChart"
                            :options="options"
                            :data="top_visit_result"
                            /> 
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>
            
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cantidad de Usuarios por Ciudad</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <GChart
                            type="PieChart"
                            :options="options"
                            :data="city_result"
                            /> 
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cantidad de Usuarios por Región</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <GChart
                            type="PieChart"
                            :options="options"
                            :data="region_result"
                            /> 
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cantidad de Páginas más Visitadas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <GChart
                            type="PieChart"
                            :options="options"
                            :data="page_result"
                            /> 
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
    
</template>

<script>
    import vPagination from 'vue-plain-pagination';
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    import { GChart } from 'vue-google-charts/legacy'

    export default {
        created() {
            this.getRol();
            this.getTopVisit();
            this.getPosts();
            this.getCityGraphic();
            this.getRegionGraphic();
            this.getPageGraphic();
            this.storeAudit();
        },
        methods: {
            getPosts() {
                this.loading = true;

                axios.get('/api/metric?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.json_data = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                });
            },
            getTopVisit() {
                axios.get('/api/metric/top_visit?api_token='+App.apiToken)
                .then(response => {
                    this.top_visit_result = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getCityGraphic() {
                axios.get('/api/metric/city?api_token='+App.apiToken)
                .then(response => {
                    this.city_result = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getRegionGraphic() {
                axios.get('/api/metric/region?api_token='+App.apiToken)
                .then(response => {
                    this.region_result = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPageGraphic() {
                axios.get('/api/metric/page?api_token='+App.apiToken)
                .then(response => {
                    this.page_result = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            storeAudit() {
                let formData = new FormData();
                formData.append('page', 'Resultado de las Métricas');
               
                axios.post('/api/audit/store?api_token='+App.apiToken, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            }
        },
        components: { 
            vPagination,
            ClipLoader,
            GChart
        },
        data: function() {
            return {
                color: '#0A2787',
                loading: false,
                form: {
                    rol_id: null
                },
                branch_office_posts: [],
                supervisor_posts: [],
                rol_id: this.rol_id,
                postsSelected: "",
                posts: [],
                top_visit_result: [],
                city_result: [],
                json_data: [],
                region_result: [],
                page_result: [],
                data: [
                    ['Daily Routine', 'Hours per Day'],
                    ['Work',     14],
                    ['Eat',      1],
                    ['Reading',  2],
                    ['Exercise', 2],
                    ['Sleep',    5]
                ],
                options: {
                    width: 800,
                    height: 500
                },
                currentPage: 1,
                total: 0,
                rowsQuantity: ''
            }
        }
    }
</script>
