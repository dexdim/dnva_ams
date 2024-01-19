<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="search-container">
                                <div class="search-container">
                                    <input
                                        v-model="searchQuery"
                                        @keyup.enter="search"
                                        placeholder="Search"
                                        class="form-control"
                                    />
                                    <span class="search-icon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <div class="card-tools">
                                    <download-excel
                                        :data="all"
                                        class="btn btn-sm btn-primary"
                                        >Download CSV</download-excel
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary"
                                        @click="newInventory"
                                        v-if="$gate.isAdmin()"
                                    >
                                        <i class="fa fa-plus-square"></i>
                                        Add New
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-hover table-bordered table-condensed"
                                >
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th width="7.5%">ID Code</th>
                                            <!--<th width="2%">QR</th>-->
                                            <th width="5%">Category</th>
                                            <th width="20%">Description</th>
                                            <th width="10%">Serial Number</th>
                                            <th width="10%">User</th>
                                            <th width="10%">Email</th>
                                            <th width="7.5%">Status</th>
                                            <th width="7.5%">Date</th>
                                            <th width="5%">PIC</th>
                                            <th
                                                width="7.5%"
                                                v-if="$gate.isAdmin()"
                                            >
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="inventory in inventories.data"
                                            :key="inventory.id"
                                        >
                                            <td
                                                class="text-center text-bold"
                                                style="vertical-align: middle"
                                            >
                                                <a
                                                    href="#"
                                                    @click="
                                                        viewInventory(inventory)
                                                    "
                                                >
                                                    {{ inventory.idcode }}
                                                </a>
                                            </td>
                                            <!--<td class="text-center">
                                                <img
                                                    :src="
                                                        'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' +
                                                        inventory.idcode
                                                    "
                                                    width="30px"
                                                />
                                            </td>-->
                                            <td style="vertical-align: middle">
                                                {{ inventory.category.name }}
                                            </td>
                                            <td style="vertical-align: middle">
                                                {{ inventory.description }}
                                            </td>
                                            <td style="vertical-align: middle">
                                                {{ inventory.serialnumber }}
                                            </td>
                                            <td style="vertical-align: middle">
                                                {{ inventory.name }}
                                            </td>
                                            <td style="vertical-align: middle">
                                                {{ inventory.email }}
                                            </td>
                                            <td
                                                class="text-center"
                                                style="vertical-align: middle"
                                            >
                                                <h6>
                                                    <span
                                                        v-bind:class="{
                                                            'badge badge-success':
                                                                inventory.status ===
                                                                'Deployed',
                                                            'badge badge-primary':
                                                                inventory.status ===
                                                                'Storage',
                                                            'badge badge-warning':
                                                                inventory.status ===
                                                                'In Service',
                                                            'badge badge-danger':
                                                                inventory.status ===
                                                                'Broken',
                                                            'badge badge-secondary':
                                                                inventory.status ===
                                                                'Sold'
                                                        }"
                                                    >
                                                        {{
                                                            inventory.status
                                                        }}</span
                                                    >
                                                </h6>
                                            </td>
                                            <td
                                                class="text-center"
                                                style="vertical-align: middle"
                                            >
                                                {{ inventory.checkdate }}
                                            </td>
                                            <td
                                                class="text-center"
                                                style="vertical-align: middle"
                                            >
                                                {{ inventory.checkedby }}
                                            </td>
                                            <td
                                                class="text-center"
                                                style="vertical-align: middle"
                                                v-if="$gate.isAdmin()"
                                            >
                                                <a
                                                    href="#"
                                                    @click="
                                                        editInventory(inventory)
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
                                                        duplicateInventory(
                                                            inventory.id
                                                        )
                                                    "
                                                    class="a"
                                                >
                                                    <i
                                                        class="fa fa-copy green"
                                                    ></i>
                                                </a>
                                                <a
                                                    href="#"
                                                    @click="
                                                        deleteInventory(
                                                            inventory.id
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
                                    :data="inventories"
                                    @pagination-change-page="loadInventory"
                                    :limit="25"
                                ></pagination>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <!-- Modal View-->
            <div
                class="modal fade"
                id="view"
                tabindex="-1"
                role="dialog"
                aria-labelledby="view"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Inventory</h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>ID Code</label>
                                        <div>{{ form.idcode }}</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Category</label>
                                        <div>{{ form.category.name }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Description</label>
                                        <div>{{ form.description }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Brand</label>
                                        <div>{{ form.brand }}</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Serial Number</label>
                                        <div>{{ form.serialnumber }}</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Supplier</label>
                                        <div>{{ form.supplier }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Purchase Cost</label>
                                        <div>{{ form.purchasecost }}</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Purchase Date</label>
                                        <div>{{ form.purchasedate }}</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Windows License</label>
                                        <div>{{ form.license }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>User</label>
                                        <div>{{ form.name }}</div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Status</label>
                                        <div>{{ form.status }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <div>{{ form.email }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Department</label>
                                        <div>{{ form.dept }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>User History</label>
                                        <div>{{ form.history }}</div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Notes</label>
                                        <div>{{ form.notes }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Check Date</label>
                                        <div>{{ form.checkdate }}</div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Checked By</label>
                                        <div>{{ form.checkedby }}</div>
                                    </div>
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Add & Update-->
            <div
                class="modal fade"
                id="addNew"
                tabindex="-1"
                role="dialog"
                aria-labelledby="addNew"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-show="!editmode">
                                Create New Inventory
                            </h5>
                            <h5 class="modal-title" v-show="editmode">
                                Edit Inventory
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

                        <form
                            @submit.prevent="
                                editmode ? updateInventory() : createInventory()
                            "
                        >
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>ID Code</label>
                                        <input
                                            v-model="form.idcode"
                                            type="text"
                                            name="idcode"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has('idcode')
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="idcode"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Category</label>
                                        <select
                                            class="form-control"
                                            v-model="form.category_id"
                                        >
                                            <!--<option
                                                v-for="(
                                                    cat, index
                                                ) in categories"
                                                :key="index"
                                                :value="index"
                                                :selected="
                                                    index == form.category_id
                                                "
                                            >
                                                {{ cat }}
                                            </option>-->
                                            <option
                                                v-for="cat in sortedCategories"
                                                :key="cat.id"
                                                :value="cat.id"
                                                :selected="
                                                    cat.id == form.category_id
                                                "
                                            >
                                                {{ cat.name }}
                                            </option>
                                        </select>
                                        <!--<v-select
                                            v-model="form.category_id"
                                            :options="sortedCategories"
                                            label="name"
                                            value="id"
                                            :reduce="(name) => name.id"
                                        />-->
                                        <has-error
                                            :form="form"
                                            field="category_id"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Description</label>
                                        <input
                                            v-model="form.description"
                                            type="text"
                                            name="description"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has(
                                                        'description'
                                                    )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="description"
                                        ></has-error>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Brand</label>
                                        <input
                                            v-model="form.brand"
                                            type="text"
                                            name="brand"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has('brand')
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="brand"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Serial Number</label>
                                        <input
                                            v-model="form.serialnumber"
                                            type="text"
                                            name="serialnumber"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has(
                                                        'serialnumber'
                                                    )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="serialnumber"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Supplier</label>
                                        <input
                                            v-model="form.supplier"
                                            type="text"
                                            name="supplier"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has('supplier')
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="supplier"
                                        ></has-error>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Purchase Cost</label>
                                        <input
                                            v-model="form.purchasecost"
                                            type="text"
                                            name="purchasecost"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has(
                                                        'purchasecost'
                                                    )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="purchasecost"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Purchase Date</label>
                                        <date-picker
                                            v-model="form.purchasedate"
                                            value-type="format"
                                            format="DD MMMM YYYY"
                                            name="purchasedate"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has(
                                                        'purchasedate'
                                                    )
                                            }"
                                        ></date-picker>
                                        <has-error
                                            :form="form"
                                            field="purchasedate"
                                        ></has-error>
                                    </div>
                                    <!--<div class="form-group col-md-3">
                                        <label>Windows License</label>
                                        <input
                                            v-model="form.license"
                                            type="text"
                                            name="license"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has('license')
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="license"
                                        ></has-error>
                                    </div>-->
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>User</label>
                                        <v-select
                                            v-model="form.name"
                                            :options="sortedEmployees"
                                            label="name"
                                            :reduce="(name) => name.name"
                                        />
                                        <has-error
                                            :form="form"
                                            field="name"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Status</label>
                                        <select
                                            class="form-control"
                                            v-model="form.status"
                                        >
                                            <option
                                                v-for="stat in sortedStatuses"
                                                :key="stat"
                                                :value="stat"
                                                :selected="stat == form.status"
                                            >
                                                {{ stat }}
                                            </option>
                                        </select>
                                        <has-error
                                            :form="form"
                                            field="status"
                                        ></has-error>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input
                                            type="text"
                                            name="email"
                                            class="form-control-plaintext"
                                            v-model="form.email"
                                            disabled
                                        />
                                        <has-error
                                            :form="form"
                                            field="email"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Department</label>
                                        <input
                                            type="text"
                                            name="dept"
                                            class="form-control-plaintext"
                                            v-model="form.dept"
                                            disabled
                                        />
                                        <has-error
                                            :form="form"
                                            field="dept"
                                        ></has-error>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>User History</label>
                                        <v-select
                                            v-model="form.history"
                                            :options="sortedEmployees"
                                            label="name"
                                            :reduce="(name) => name.name"
                                        />
                                        <has-error
                                            :form="form"
                                            field="history"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Notes</label>
                                        <input
                                            v-model="form.notes"
                                            type="text"
                                            name="notes"
                                            class="form-control"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has('notes')
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="notes"
                                        ></has-error>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Check Date</label>
                                        <date-picker
                                            v-model="form.checkdate"
                                            value-type="format"
                                            format="DD MMMM YYYY"
                                            name="checkdate"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.has('checkdate')
                                            }"
                                        ></date-picker>
                                        <has-error
                                            :form="form"
                                            field="checkdate"
                                        ></has-error>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Checked By</label>
                                        <select
                                            class="form-control"
                                            v-model="form.checkedby"
                                        >
                                            <option>Dimas</option>
                                            <option>Kinan</option>
                                        </select>
                                        <has-error
                                            :form="form"
                                            field="checkedby"
                                        ></has-error>
                                    </div>
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
            <!--<pre>{{ inventories }}</pre>-->
        </div>
    </section>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
    data() {
        return {
            editmode: false,
            inventories: {},
            all: [],
            searchQuery: '',
            form: new Form({
                id: '',
                idcode: '',
                category: '',
                category_id: '',
                description: '',
                brand: '',
                serialnumber: '',
                supplier: '',
                purchasecost: '',
                purchasedate: '',
                license: '',
                name: '',
                email: '',
                status: '',
                notes: '',
                history: '',
                checkdate: '',
                checkedby: ''
            }),
            statuses: [
                'Storage',
                'Deployed',
                'Working',
                'Lost',
                'In Service',
                'Broken'
            ],
            categories: [],
            employees: []
        }
    },
    methods: {
        loadAll() {
            // if(this.$gate.isAdmin()){
            axios
                .get('/api/inventory/all')
                .then((data) => (this.all = data.data.data))
                .catch((error) => console.log(error))
            // }
        },

        loadInventory(page = 1) {
            // if(this.$gate.isAdmin()){
            axios
                .get(
                    `/api/inventory/search?page=${page}&search=${this.searchQuery}`
                )
                .then((data) => (this.inventories = data.data.data))
                .catch((error) => console.log(error))
            this.$Progress.finish()
            // }
        },

        search() {
            this.loadInventory()
        },

        loadCategory() {
            axios
                .get(`/api/category/list`)
                .then((data) => (this.categories = data.data.data))
                .catch((error) => console.log(error))
        },
        loadEmployee() {
            axios
                .get(`/api/employee/list`)
                .then((data) => (this.employees = data.data.data))
                .catch((error) => console.log(error))
        },

        viewInventory(inventory) {
            this.viewmode = true
            this.form.reset()
            $('#view').modal('show')
            this.form.fill(inventory)
        },

        editInventory(inventory) {
            this.editmode = true
            this.form.reset()
            $('#addNew').modal('show')
            this.form.fill(inventory)
        },

        newInventory() {
            this.editmode = false
            this.form.reset()
            $('#addNew').modal('show')
        },

        createInventory() {
            this.$Progress.start()
            this.form
                .post(`/api/inventory`)
                .then((response) => {
                    if (response.data.success) {
                        $('#addNew').modal('hide')
                        Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        })
                        this.$Progress.finish()
                        this.loadInventory()
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        })
                        this.$Progress.failed()
                    }
                })
                .catch(() => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Some error occured! Please try again'
                    })
                })
        },

        updateInventory() {
            this.$Progress.start()
            this.form
                .put(`api/inventory/` + this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.$Progress.finish()
                    //Fire.$emit('AfterCreate')
                    this.loadInventory()
                    //this.reloadInventory(this.form.id)
                })
                .catch(() => {
                    this.$Progress.fail()
                })
        },

        deleteInventory(id) {
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
                        .delete(`api/inventory/` + id)
                        .then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Your record has been deleted.',
                                'success'
                            )
                            //Fire.$emit('AfterCreate')
                            this.loadInventory()
                        })
                        .catch((data) => {
                            Swal.fire('Failed!', data.message, 'warning')
                        })
                }
            })
        },

        duplicateInventory(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will duplicate a record of this row',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, duplicate it!'
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.form
                        .post(`api/inventory/duplicate/` + id)
                        .then(() => {
                            Swal.fire(
                                'Duplicated!',
                                'Your record has been duplicated.',
                                'success'
                            )
                            //Fire.$emit('AfterCreate')
                            this.loadInventory()
                        })
                        .catch((data) => {
                            Swal.fire('Failed!', data.message, 'warning')
                        })
                }
            })
        },

        searchInventory() {
            this.loadInventory()
        }
    },

    mounted() {},

    created() {
        this.$Progress.start()
        this.loadInventory()
        this.loadAll()
        this.loadCategory()
        this.loadEmployee()
        this.$Progress.finish()
    },

    computed: {
        mappedCategories() {
            // Transform the original object into an array of objects
            return Object.keys(this.categories).map((id) => ({
                id,
                name: this.categories[id]
            }))
        },
        sortedCategories() {
            // Sort the array based on the 'name' property
            return this.mappedCategories
                .slice()
                .sort((a, b) => a.name.localeCompare(b.name))
        },

        sortedEmployees() {
            return this.employees.slice().sort((a, b) => {
                return a.name.localeCompare(b.name)
            })
        },
        sortedStatuses() {
            return this.statuses.slice().sort((a, b) => {
                return a.localeCompare(b)
            })
        }
    },
    components: { DatePicker, 'v-select': vSelect },

    watch: {
        'form.name'(value) {
            const selectedOption = this.employees.find(
                (emp) => emp.name === value
            )
            this.form.email = selectedOption ? selectedOption.email : ''
            this.form.dept = selectedOption ? selectedOption.dept : ''
        },

        'form.status'(value) {
            if (
                value === 'Storage' ||
                value === 'Working' ||
                value === 'Broken' ||
                value === 'Sold'
            ) {
                this.form.name = this.employees.find(
                    (emp) => emp.name === '-'
                ).name
            } else {
                this.employees
            }
        }
    }
}
</script>

<style scoped>
.card {
    width: 100%; /* Adjust the card width as needed */
}

.table-responsive {
    overflow-x: auto;
    max-width: 100%;
}

.table {
    overflow-y: scroll;
    width: 100%;
}

.pagination-container {
    max-height: 200px;
    overflow-y: auto;
}

.a {
    margin: 5px;
}

.search-container {
    display: flex;
    align-items: center;
    width: 40%;
}

.search-icon {
    position: relative;
    top: 2px;
    right: 30px;
    color: #3490dc;
    cursor: pointer;
}

.search-icon i {
    font-size: 18px;
}

.card-tools {
    position: absolute;
    right: 25px;
}
</style>
