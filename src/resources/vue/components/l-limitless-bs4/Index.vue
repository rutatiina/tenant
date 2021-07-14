<template>

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4>
                        <i class="icon-users2 mr-2"></i>
                        <span class="font-weight-semibold">Organisations</span>
                    </h4>
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="/" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                        <span class="breadcrumb-item active">Organisations</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

                <div class="header-elements d-none">
                    <div class="breadcrumb justify-content-center">
                        <router-link to="/settings/organisations/create" class=" btn btn-danger btn-sm rounded-round font-weight-bold">
                            <i class="icon-user-plus mr-1"></i>
                            New Organisation
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page header -->


        <!-- Content area -->
        <div class="content border-0 p-0">

            <loading-animation></loading-animation>

            <!-- Basic table -->
            <div class="card shadow-none rounded-0 border-0">

                <div class="card-body" v-if="!this.$root.loading && tableData.payload.data.length === 0">

                    <h6>
                        <i class="icon-files-empty mr-2"></i>
                        No records found
                    </h6>

                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                        <tr class="table-active">
                            <th scope="col" class="font-weight-bold" width="20"> </th>
                            <th scope="col" class="font-weight-bold">Name</th>
                            <th scope="col" class="font-weight-bold" nowrap="">Country</th>
                            <th scope="col" class="font-weight-bold" nowrap="">Base currency</th>
                            <th scope="col" class="font-weight-bold" nowrap="">Fiscal Year</th>
                            <th scope="col" class="font-weight-bold" nowrap="">Languages</th>
                            <th scope="col" class="font-weight-bold" nowrap="">Time Zone</th>
                            <th scope="col" class="font-weight-bold" nowrap="">Date Format</th>
                            <th scope="col" class="font-weight-bold" nowrap=""> </th>
                        </tr>

                        <tr v-for="row in tableData.payload.data"
                            @click="onRowClick(row)">
                            <td v-on:click.stop
                                class="cursor-pointer font-weight-bold">
                                <a :href="'/settings/organisations/'+row.id+'/switch'">
                                    <i class="icon-loop position-left"></i>
                                </a>
                            </td>
                            <td class="cursor-pointer font-weight-semibold" nowrap>{{row.name}}</td>
                            <td class="cursor-pointer">{{row.country}}</td>
                            <td class="cursor-pointer">{{row.base_currency}}</td>
                            <td class="cursor-pointer">{{row.fiscal_year}}</td>
                            <td class="cursor-pointer">{{row.language}}</td>
                            <td class="cursor-pointer">{{row.time_zone}}</td>
                            <td class="cursor-pointer">{{row.date_format}}</td>
                            <td class="cursor-pointer" v-on:click.stop>
                                <div class="list-icons">
                                    <a href="#" class="list-icons-item text-primary-600" @click.prevent="onRowClick(row)"><i class="icon-pencil7"></i></a>
                                    <a href="#" class="list-icons-item text-danger-600" @click.prevent="deleteTxns(row)" title="Delete all transactions"><i class="icon-trash"></i></a>
                                    <!--<a href="#" class="list-icons-item text-teal-600"><i class="icon-cog6"></i></a>-->
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <rg-tables-pagination v-bind:table-data-prop="tableData"></rg-tables-pagination>

                </div>

            </div>
            <!-- /basic table -->

        </div>
        <!-- /content area -->


        <!-- Footer -->

        <!-- /footer -->

    </div>
    <!-- /main content -->

</template>

<script>

    export default {
        watch: {
            '$route.query.page': function (page) {
                this.tableData.url = this.$router.currentRoute.path + '?page='+page;
            }
        },
        mounted() {
            this.$root.appMenu('settings')

            this.tableData.initiate = true

            let currentObj = this;

            if (currentObj.$route.query.page === undefined) {
                currentObj.tableData.url = this.$router.currentRoute.path; //'/crbt/transactions';
            } else {
                currentObj.tableData.url = this.$router.currentRoute.path + '?page='+currentObj.$route.query.page;
            }

        },
        methods: {
            onRowClick(row) {
                this.$router.push({ path: '/settings/organisations/'+row.id + '/edit' })
            },
            deleteTxns(row) {

                let swalInit = swal.mixin({
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-light'
                })

                swalInit({
                    title: row.name, //'Are you sure?',
                    html: 'Are you sure you want to delete <b>all</b> transactions',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    preConfirm: function() {
                        return axios.delete('/settings/organisations/'+row.id + '/delete-transactions')
                            .then( (response) => {
                                console.log(response)
                                return response.data.message
                            })
                    }
                }).then((result) => {
                    console.log(result)
                    if (result.value) {
                        Swal.fire({
                            //type: 'warning',
                            title: result.value,
                            showConfirmButton: false,
                        })
                    }
                })

                //console.log('Delete all transactions')
            },
        },
        ready:function(){},
        beforeUpdate: function () {},
        updated: function () {
            InputsCheckboxesRadios.initComponents();
        }
    }
</script>
