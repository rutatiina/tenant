<template>

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4>
                        <i class="icon-file-plus"></i>
                        {{pageTitle}}
                    </h4>

                </div>

            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Organisation</a>
                        <span class="breadcrumb-item active">Create</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

                <div class="header-elements">
                    <div class="breadcrumb justify-content-center">
                        <router-link to="/settings/organisations" class=" btn btn-danger btn-sm rounded-round font-weight-bold">
                            <i class="icon-users2 mr-1"></i>
                            Organisations
                        </router-link>
                    </div>
                </div>

            </div>

        </div>
        <!-- /page header -->

        <!-- Content area -->
        <div class="content border-0 padding-0">

            <loading-animation></loading-animation>

            <!-- Form horizontal -->
            <div class="card shadow-none rounded-0 border-0">

                <div class="card-body p-0">

                    <form v-if="!this.$root.loading"
                          @submit="formSubmit"
                          action=""
                          method="post"
                          class="max-width-1040"
                          style="margin-bottom: 100px;"
                          autocomplete="off">

                        <fieldset class="">

                            <div class="form-group row">
                                <label class="col-lg-2 control-label">
                                    Name
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" v-model="attributes.name" value=""
                                           class="form-control "
                                           placeholder="Organisation name">
                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Service
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsServices"
                                                       v-model="attributes.service_id"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select Service                       ">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Industry
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsIndustries"
                                                       v-model="attributes.industry"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select industry">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Country
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsCountries"
                                                       v-model="attributes.country"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select Country">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Address
                                </label>
                                <div class="col-lg-10">
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <input type="text" v-model="attributes.street_line_1" value="" class="form-control " placeholder="Street line #1">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <input type="text" v-model="attributes.street_line_2" value="" class="form-control " placeholder="Street line #2">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4">
                                            <input type="text" v-model="attributes.city" value="" class="form-control " placeholder="City">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" v-model="attributes.state_province" value="" class="form-control " placeholder="State/Province">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" v-model="attributes.zip_postal_code" value="" class="form-control " placeholder="Zip/Postal Code">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="text" v-model="attributes.phone" value="" class="form-control " placeholder="Phone">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" v-model="attributes.fax" value="" class="form-control " placeholder="Fax">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" v-model="attributes.website" value="" class="form-control " placeholder="Website">
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Base currency
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsCurrencies"
                                                       v-model="attributes.base_currency"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select currency">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Fiscal Year
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="fiscalYear"
                                                       v-model="attributes.fiscal_year"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select fiscal year">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Languages
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsLanguages"
                                                       v-model="attributes.language"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select language">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Time Zone
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsTimeZones"
                                                       v-model="attributes.time_zone"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select timezone">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Date Format
                                </label>
                                <div class="col-lg-6">
                                    <model-list-select :list="globalsDateFormats"
                                                       v-model="attributes.date_format"
                                                       option-value="value"
                                                       option-text="text"
                                                       placeholder="Select Date Format">
                                    </model-list-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Company ID
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" v-model="attributes.company_id_value" value="" class="form-control " placeholder="Company ID">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">
                                    Tax ID
                                </label>
                                <div class="col-lg-6">
                                    <input type="text" v-model="attributes.tax_id_value" value="" class="form-control " placeholder="Tax ID">
                                </div>
                            </div>

                        </fieldset>



                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"> </label>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-danger font-weight-bold">
                                    <i class="icon-user-plus"></i> {{pageTitle}}
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
            <!-- /form horizontal -->


        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->

</template>

<script>

    export default {
        data() {
            return {
                pageTitle: 'Organisations',
                urlPost: '/settings/organisations',
                fiscalYear: [
                    { value: 'January December', text: 'January December' },
                    { value: 'February January', text: 'February January' },
                    { value: 'March February', text: 'March February' },
                    { value: 'April March', text: 'April March' },
                    { value: 'May April', text: 'May April' },
                    { value: 'June May', text: 'June May' },
                    { value: 'July June', text: 'July June' },
                    { value: 'August July', text: 'August July' },
                    { value: 'September August', text: 'September August' },
                    { value: 'October September', text: 'October September' },
                    { value: 'November October', text: 'November October' },
                    { value: 'December November', text: 'December November' }
                ],
                selectedCurrencies: [],
                selectedTaxes: [],
                attributes: [],
                countries: [],
                currencies: [],
                taxes: [],
            }
        },
        mounted() {

            //console.log('ContactsCreate: Component mounted');

            this.fetchAttributes()
            this.appFetchGlobalsCurrencies()
            this.appFetchGlobalsCountries()
            this.appFetchGlobalsLanguages()
            this.appFetchGlobalsDateFormats()
            this.appFetchGlobalsIndustries()
            this.appFetchGlobalsServices()
            this.appFetchGlobalsTimeZones()

        },
        watch: {
            $route: function () {
                this.fetchAttributes()
            }
        },
        methods: {
            async fetchAttributes() {
                //console.log('fetchAttributes')

                try {

                    return await axios.get(this.$route.fullPath)
                        .then(response => {

                            this.pageTitle = response.data.pageTitle
                            this.urlPost = response.data.urlPost
                            this.attributes = response.data.attributes

                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error); //test
                        })
                        .finally(function (response) {
                            // always executed this is supposed
                        })

                } catch (e) {
                    console.log(e); //test
                }
            },
            formSubmit(e) {

                e.preventDefault();

                let currentObj = this;

                PNotify.removeAll();

                let PNotifySettings = {
                    title: false, //'Processing',
                    text: 'Please wait as we do our thing',
                    addclass: 'bg-warning-400 border-warning-400',
                    hide: false,
                    buttons: {
                        closer: false,
                        sticker: false
                    }
                };

                let notice = new PNotify(PNotifySettings)

                //console.log(this.attributes);

                axios.post(currentObj.urlPost, this.attributes)
                    .then(function (response) {

                        //PNotify.removeAll();

                        PNotifySettings.text = response.data.messages.join("\n");

                        if(response.data.status === true)
                        {
                            PNotifySettings.title = 'Success';
                            PNotifySettings.type = 'success';
                            PNotifySettings.addclass = 'bg-success-400 border-success-400';
                            notice.update(PNotifySettings);
                            currentObj.$router.push({ path: response.data.callback })
                        }
                        else
                        {
                            PNotifySettings.title = '! Error';
                            PNotifySettings.type = 'error';
                            PNotifySettings.addclass = 'bg-warning-400 border-warning-400';
                            notice.update(PNotifySettings);
                        }

                        //let notice = new PNotify(PNotifySettings);

                        notice.get().click(function() {
                            notice.remove();
                        });

                        //currentObj.response = response.data;
                    })
                    .catch(function (error) {
                        currentObj.response = error;
                    });
            },
        },
        ready:function(){

        },
        beforeUpdate: function () {
            //console.log('beforeUpdate')
        },
        updated: function () {

            //console.log('updated:: dom updated -')

        }
    }
</script>
