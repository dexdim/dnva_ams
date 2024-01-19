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
                                @click="newCategory"
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
                                            <th>Description</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="category in categories.data"
                                            :key="category.id"
                                        >
                                            <td class="text-center">
                                                {{ category.id }}
                                            </td>
                                            <td class="text-capitalize">
                                                {{ category.name }}
                                            </td>
                                            <td>{{ category.description }}</td>
                                            <td class="text-center">
                                                <a
                                                    href="#"
                                                    @click="
                                                        editCategory(category)
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
                                                        deleteCategory(
                                                            category.id
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
                            <pagination
                                :data="categories"
                                @pagination-change-page="getResults"
                            ></pagination>
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
                                Create New Category
                            </h5>
                            <h5 class="modal-title" v-show="editmode">
                                Update Category
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
                                editmode ? updateCategory() : createCategory()
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
                                    <label>Description</label>
                                    <input
                                        v-model="form.description"
                                        type="text"
                                        name="description"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('description')
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="description"
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
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            editmode: false,
            categories: {},
            form: new Form({
                id: '',
                name: '',
                description: ''
            })
        }
    },
    methods: {
        getResults(page = 1) {
            this.$Progress.start()

            axios
                .get('/api/category?page=' + page)
                .then(({ data }) => (this.categories = data.data))
                .catch((error) => console.log(error))

            this.$Progress.finish()
        },

        updateCategory() {
            this.$Progress.start()
            this.form
                .put('/api/category/' + this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.$Progress.finish()
                    //  Fire.$emit('AfterCreate');

                    this.loadCategories()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        editCategory(category) {
            this.editmode = true
            this.form.reset()
            $('#addNew').modal('show')
            this.form.fill(category)
        },

        newCategory() {
            this.editmode = false
            this.form.reset()
            $('#addNew').modal('show')
        },

        loadCategories() {
            if (this.$gate.isAdmin()) {
                axios
                    .get('/api/category')
                    .then(({ data }) => (this.categories = data.data))
            }
        },

        createCategory() {
            this.form
                .post('/api/category')
                .then((response) => {
                    $('#addNew').modal('hide')

                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })

                    this.$Progress.finish()
                    this.loadCategories()
                })
                .catch(() => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    })
                })
        },

        deleteCategory(id) {
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
                        .delete('api/category/' + id)
                        .then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Category has been deleted.',
                                'success'
                            )
                            // Fire.$emit('AfterCreate');
                            this.loadCategories()
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
        this.loadCategories()
        this.$Progress.finish()
    }
}
</script>

<style scoped>
.a {
    margin: 5px;
}

.card-header {
    position: relative;
    height: 60px;
}
</style>
