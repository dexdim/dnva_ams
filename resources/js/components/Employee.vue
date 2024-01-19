<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" v-if="$gate.isAdmin()">
                        <div class="card-header">
                            <button
                                type="button"
                                class="btn btn-sm btn-primary"
                                style="
                                    position: absolute;
                                    top: 15px;
                                    right: 25px;
                                "
                                @click="newEmployee"
                            >
                                <i class="fa fa-plus-square"></i>
                                Add New
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table
                                    class="table table-hover table-bordered table-condensed"
                                >
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="20%">Name</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="employee in employees.data"
                                            :key="employee.id"
                                        >
                                            <td class="text-center">
                                                {{ employee.id }}
                                            </td>
                                            <td class="text-capitalize">
                                                {{ employee.name }}
                                            </td>
                                            <td>{{ employee.email }}</td>
                                            <td>{{ employee.dept }}</td>
                                            <td class="text-center">
                                                <a
                                                    href="#"
                                                    @click="
                                                        editEmployee(employee)
                                                    "
                                                    class="a"
                                                >
                                                    <i
                                                        class="fa fa-edit blue"
                                                    ></i>
                                                </a>
                                                <a
                                                    href="#"
                                                    @click="
                                                        deleteEmployee(
                                                            employee.id
                                                        )
                                                    "
                                                    class="a"
                                                >
                                                    <i
                                                        class="fa fa-trash red"
                                                    ></i>
                                                </a>
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
                                    :data="employees"
                                    @pagination-change-page="getResults"
                                    :limit="25"
                                ></pagination>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div v-if="!$gate.isAdmin()">
                <not-found></not-found>
            </div>

            <!-- Modal -->
            <div
                class="modal fade"
                id="addNew"
                tabindex="-1"
                role="dialog"
                aria-labelledby="addNew"
                aria-hidden="true"
            >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-show="!editmode">
                                Create New Employee
                            </h5>
                            <h5 class="modal-title" v-show="editmode">
                                Update Employee
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!-- <form @submit.prevent="createUser"> -->
                        <form
                            @submit.prevent="
                                editmode ? updateEmployee() : createEmployee()
                            "
                        >
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('name')
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="name"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input
                                        v-model="form.email"
                                        type="text"
                                        name="description"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('email')
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="email"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <input
                                        v-model="form.dept"
                                        type="text"
                                        name="description"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('department')
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="department"
                                    ></has-error>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                >
                                    Close
                                </button>
                                <button
                                    v-show="editmode"
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    Update
                                </button>
                                <button
                                    v-show="!editmode"
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--<pre>{{ employees }}</pre>-->
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            editmode: false,
            employees: {},
            form: new Form({
                id: '',
                name: '',
                email: '',
                dept: ''
            })
        }
    },
    methods: {
        getResults(page = 1) {
            this.$Progress.start()

            axios
                .get('/api/employee?page=' + page)
                .then(({ data }) => (this.employees = data.data))
                .catch((error) => console.log(error))

            this.$Progress.finish()
        },

        updateEmployee() {
            this.$Progress.start()
            this.form
                .put('/api/employee/' + this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.$Progress.finish()
                    //  Fire.$emit('AfterCreate');

                    this.loadEmployee()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        editEmployee(employee) {
            this.editmode = true
            this.form.reset()
            $('#addNew').modal('show')
            this.form.fill(employee)
        },

        newEmployee() {
            this.editmode = false
            this.form.reset()
            $('#addNew').modal('show')
        },

        loadEmployee() {
            if (this.$gate.isAdmin()) {
                axios
                    .get('/api/employee')
                    .then(({ data }) => (this.employees = data.data))
            }
        },

        createEmployee() {
            this.form
                .post('/api/employee')
                .then((response) => {
                    $('#addNew').modal('hide')

                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })

                    this.$Progress.finish()
                    this.loadEmployee()
                })
                .catch(() => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    })
                })
        },

        deleteEmployee(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.form
                        .delete(`api/employee/` + id)
                        .then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Employee has been deleted.',
                                'success'
                            )
                            // Fire.$emit('AfterCreate');
                            this.loadEmployee()
                        })
                        .catch((data) => {
                            Swal.fire('Failed!', data.message, 'warning')
                        })
                }
            })
        }
    },
    mounted() {
        console.log('Component mounted.')
    },
    created() {
        this.$Progress.start()
        this.loadEmployee()
        this.$Progress.finish()
    }
}
</script>

<style scoped>
.pagination-container {
    max-height: 200px;
    overflow-y: auto;
}

.a {
    margin: 5px;
}

.card-header {
    position: relative;
    height: 60px;
}
</style>
