<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"
                            ><i class="fas fa-check-circle"></i
                        ></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Deployed</span>
                            <span class="info-box-number">{{
                                counts.deployed
                            }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"
                            ><i class="fas fa-archive"></i
                        ></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Storage</span>
                            <span class="info-box-number">{{
                                counts.storage
                            }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"
                            ><i class="fas fa-tools"></i
                        ></span>
                        <div class="info-box-content">
                            <span class="info-box-text">In Service</span>
                            <span class="info-box-number">{{
                                counts.inservice
                            }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"
                            ><i class="fas fa-times"></i
                        ></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Broken</span>
                            <span class="info-box-number">{{
                                counts.broken
                            }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left col -->
                        <div class="col-8">
                            <!-- MAP & BOX PANE -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Device in Storage
                                    </h3>
                                    <div class="card-tools">
                                        <button
                                            type="button"
                                            class="btn btn-tool"
                                            data-card-widget="collapse"
                                        >
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-hover table-bordered table-condensed"
                                        >
                                            <thead
                                                class="thead-dark text-center"
                                            >
                                                <tr>
                                                    <th width="10%">ID Code</th>
                                                    <th width="10%">
                                                        Category
                                                    </th>
                                                    <th width="50%">
                                                        Description
                                                    </th>
                                                    <th width="30%">
                                                        User History
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="instorage in inStorage.data"
                                                    :key="instorage.id"
                                                >
                                                    <td
                                                        class="text-center text-bold"
                                                        style="
                                                            vertical-align: middle;
                                                        "
                                                    >
                                                        <a
                                                            href="pages/examples/invoice.html"
                                                            >{{
                                                                instorage.idcode
                                                            }}</a
                                                        >
                                                    </td>
                                                    <td
                                                        style="
                                                            vertical-align: middle;
                                                        "
                                                    >
                                                        {{
                                                            instorage.category
                                                                .name
                                                        }}
                                                    </td>
                                                    <td
                                                        style="
                                                            vertical-align: middle;
                                                        "
                                                    >
                                                        {{
                                                            instorage.description
                                                        }}
                                                    </td>
                                                    <td
                                                        style="
                                                            vertical-align: middle;
                                                        "
                                                    >
                                                        {{ instorage.history }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <div class="pagination-container">
                                        <pagination
                                            :data="inStorage"
                                            @pagination-change-page="
                                                loadInStorage
                                            "
                                            :limit="25"
                                        ></pagination>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->

                        <div class="col-md-4">
                            <!-- Info Boxes Style 2 -->
                            <div class="info-box mb-3 bg-info">
                                <span class="info-box-icon"
                                    ><i class="fas fa-laptop"></i
                                ></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Laptop</span>
                                    <span class="info-box-number"
                                        >{{ counts.laptop }} units</span
                                    >
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                            <div class="info-box mb-3 bg-info">
                                <span class="info-box-icon"
                                    ><i class="fas fa-tv"></i
                                ></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Monitor</span>
                                    <span class="info-box-number"
                                        >{{ counts.monitor }} units</span
                                    >
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                            <div class="info-box mb-3 bg-info">
                                <span class="info-box-icon"
                                    ><i class="fas fa-mobile-alt"></i
                                ></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Handphone</span>
                                    <span class="info-box-number"
                                        >{{ counts.handphone }} units</span
                                    >
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                            <div class="info-box mb-3 bg-info">
                                <span class="info-box-icon"
                                    ><i class="fas fa-server"></i
                                ></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Server</span>
                                    <span class="info-box-number"
                                        >{{ counts.server }} units</span
                                    >
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </section>
        </div>
        <!--<pre>{{ counts }}</pre>-->
        <!--/. container-fluid -->
    </section>
</template>

<script>
export default {
    data() {
        return {
            counts: [],
            inStorage: [],
            inService: []
        }
    },
    methods: {
        loadCount() {
            axios
                .get('/api/dashboard/count')
                .then((data) => (this.counts = data.data.data))
                .catch((error) => console.log(error))
        },

        loadInStorage() {
            axios
                .get('/api/dashboard/instorage')
                .then((data) => (this.inStorage = data.data.data))
                .catch((error) => console.log(error))
        },

        loadInService() {
            axios
                .get('/api/dashboard/instorage')
                .then((data) => (this.inService = data.data.data))
                .catch((error) => console.log(error))
        }
    },
    mounted() {
        console.log('Component mounted.')
    },
    created() {
        this.$Progress.start()
        this.loadCount()
        this.loadInStorage()
        this.loadInService()
        this.$Progress.finish()
    }
}
</script>

<style scoped>
.pagination-container {
    max-height: 200px;
    overflow-y: auto;
}
</style>
