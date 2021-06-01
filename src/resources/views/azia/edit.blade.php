@extends('azia.layouts.dashboard_nine')

@section('title', 'Tenant')

@section('content')

    <div class="az-content-body pl-0">

        <div class="az-content az-content-app pd-b-0">
            <div class="container ml-0" style="max-width:100%;">
                <div class="az-content-left az-content-left-invoice">
                    <div class="az-content-breadcrumb lh-1 mb-3 pl-3">
                        <span>Home</span>
                        <span>Tenant</span>
                    </div>

                    <div id="azInvoiceList" class="az-invoice-list">

                        <div class="pd-20">
                            <div class="az-content-label mg-b-5">Tenant</div>
                            <nav class="nav az-nav-column">
                                <a class="nav-link active" data-toggle="tab" href="#">Basic</a>
                                <a class="nav-link" data-toggle="tab" href="#">Payment</a>
                                <a class="nav-link" data-toggle="tab" href="#">Address</a>
                            </nav>
                        </div>

                        <div class="pd-20">
                            <div class="az-content-label mg-b-5">Accounting</div>
                            <nav class="nav az-nav-column">
                                <a class="nav-link" data-toggle="tab" href="#">Transaction Type</a>
                                <a class="nav-link" data-toggle="tab" href="#">Transaction Entree</a>
                            </nav>
                        </div>

                        <div class="pd-20">
                            <div class="az-content-label mg-b-5">API</div>
                            <nav class="nav az-nav-column">
                                <a class="nav-link" data-toggle="tab" href="#">Keys</a>
                                <a class="nav-link" data-toggle="tab" href="#">Apps</a>
                            </nav>
                        </div>

                    </div><!-- az-invoice-list -->
                </div><!-- az-content-left -->

                <div class="az-content-body az-content-body-invoice">

                    <div class="row">
                        <div class="col-sm-12">

                            <div class="az-content-label mg-b-5">Tenant information</div>
                            <p class="mg-b-20">Set the default details for the tenant.</p>

                            <hr>

                            <form class="col-md-6" action="{{url('tenant/edit')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                @method('POST')

                                <input type="hidden" name="submit" value="1" />

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Logo</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <div class="mb-3">
                                            <div class="custom-file">
                                                <input type="file" name="logo" class="custom-file-input" id="customFile">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Name</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <input type="text" name="name" value="{{$tenant->name}}" class="form-control" placeholder="Organization name / Company name / Individuals name">
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Industry</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="industry" data-placeholder="Select industry" class="select2 form-control">
                                            <option></option>
                                            <option value="information_technology" {{$tenant->country == 'information_technology' ? 'selected' : ''}}>Information technology</option>
                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Country</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="country" data-placeholder="Select country" class="select2 form-control">
                                            <option></option>
                                            @foreach ($countries as $country_code => $country_name)
                                                <option value="{{$country_code}}" {{$tenant->country == $country_code ? 'selected' : ''}}>{{$country_code}} - {{$country_name}}</option>
                                            @endforeach
                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0" style="vertical-align: top;">Address</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">

                                        <div class="row mb-2">
                                            <div class="col-lg-12 mb-15">
                                                <input type="text" name="street_line_1" value="{{$tenant->street_line_1}}" class="form-control input-roundless" placeholder="Address Street 1">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-lg-12 mb-15">
                                                <input type="text" name="street_line_2" value="{{$tenant->street_line_2}}" class="form-control input-roundless" placeholder="Address Street 2">
                                            </div>
                                        </div>

                                        <div class="row  mb-2">
                                            <div class="col-lg-4">
                                                <input type="text" name="city" value="{{$tenant->city}}" class="form-control input-roundless" placeholder="City">
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="text" name="state_province" value="{{$tenant->state_province}}" class="form-control input-roundless" placeholder="State/Province">
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="text" name="zip_postal_code" value="{{$tenant->zip_postal_code}}" class="form-control input-roundless" placeholder="Zip/Postal Code">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <input type="text" name="phone" value="{{$tenant->phone}}" class="form-control input-roundless" placeholder="Phone">
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="text" name="fax" value="{{$tenant->fax}}" class="form-control input-roundless" placeholder="Fax">
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="text" name="website" value="{{$tenant->website}}" class="form-control input-roundless" placeholder="Website">
                                            </div>
                                        </div>

                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Base currency</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="base_currency" data-placeholder="Select base currency" class="select2 form-control">
                                            <option></option>
                                            @foreach ($currencies as $currency_code => $currency_name)
                                                <option value="{{$currency_code}}" {{ $currency_code == $tenant->base_currency ? 'selected' : ''}}>{{$currency_code}} - {{$currency_name}}</option>
                                            @endforeach
                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Fiscal Year</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="fiscal_year" data-placeholder="Fiscal Year" class="select2 form-control">
                                            <option value="January December" {{$tenant->fiscal_year == 'January December' ? 'selected' : ''}}>January December</option>
                                            <option value="February January" {{$tenant->fiscal_year == 'February January' ? 'selected' : ''}}>February January</option>
                                            <option value="March February" {{$tenant->fiscal_year == 'March February' ? 'selected' : ''}}>March February</option>
                                            <option value="April March" {{$tenant->fiscal_year == 'April March' ? 'selected' : ''}}>April March</option>
                                            <option value="May April" {{$tenant->fiscal_year == 'May April' ? 'selected' : ''}}>May April</option>
                                            <option value="June May" {{$tenant->fiscal_year == 'June May' ? 'selected' : ''}}>June May</option>
                                            <option value="July June" {{$tenant->fiscal_year == 'July June' ? 'selected' : ''}}>July June</option>
                                            <option value="August July" {{$tenant->fiscal_year == 'August July' ? 'selected' : ''}}>August July</option>
                                            <option value="September August" {{$tenant->fiscal_year == 'September August' ? 'selected' : ''}}>September August</option>
                                            <option value="October September" {{$tenant->fiscal_year == 'October September' ? 'selected' : ''}}>October September</option>
                                            <option value="November October" {{$tenant->fiscal_year == 'November October' ? 'selected' : ''}}>November October</option>
                                            <option value="December November" {{$tenant->fiscal_year == 'December November' ? 'selected' : ''}}>December November</option>
                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Languages</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="language" data-placeholder="Select language" class="select2 form-control">

                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Time Zone</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="time_zone" data-placeholder="Select language" class="select2 form-control">
                                            @foreach (DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $time_zone)
                                                <option value="'.$time_zone.'" {{$time_zone == $tenant->time_zone ? 'selected' : ''}}>{{$time_zone}}</option>
                                            @endforeach
                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Date Format</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <select name="date_format" data-placeholder="Select language" class="select2 form-control">
                                            <option>yyyy-dd-mm [<?php echo date('Y-m-d'); ?>]</option>
                                        </select>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Company ID</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="company_id_name" data-placeholder="Select language" class="select2 form-control">
                                                    <option>Company ID</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="company_id_value" value="{{$tenant->company_id_value}}" class="form-control " placeholder="">
                                            </div>
                                        </div>
                                    </div><!-- col -->
                                </div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0">Tax ID</label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <div class="row mb-15">
                                            <div class="col-md-6">
                                                <select name="tax_id_name" data-placeholder="Select language" class="select2 form-control">
                                                    <option>Tax ID</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="tax_id_value" value="{{$tenant->tax_id_value}}" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    </div><!-- col -->
                                </div>


                                <div class="mg-b-40"></div>

                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <label class="form-label mg-b-0"> </label>
                                    </div><!-- col -->
                                    <div class="col-md-10 mg-t-5 mg-md-t-0">
                                        <button type="submit" class="btn btn-block btn-with-icon btn-success btn-rounded"><i class="typcn typcn-edit"></i> <strong>Update information</strong></button>
                                    </div><!-- col -->
                                </div>

                            </form>


                        </div><!-- card-body -->
                    </div><!-- card -->

                </div><!-- az-content-body -->
            </div>
        </div><!-- az-content -->

    </div><!-- az-content-body -->

@endsection

@section('script')
    <script src="lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script>
        $(document).ready(function(){
            'use strict';

            $('#customFile').change(function () {
                var t = $(this);
                t.next().text(t.val().replace(/.*[\/\\]/, ''));
            });

            new PerfectScrollbar('#azInvoiceList', {
                suppressScrollX: true
            });

            new PerfectScrollbar('.az-content-body-invoice', {
                suppressScrollX: true
            });

            $('#azInvoiceList .media').on('click', function(e){
                $(this).addClass('selected');
                $(this).siblings().removeClass('selected');

                $('body').addClass('az-content-body-show');
            });


            // Datepicker
            $('.fc-datepicker').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                dateAutoclose: true
            });

            $('#datepickerNoOfMonths').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                numberOfMonths: 2
            });

            $('.select2').select2({
                placeholder: 'Choose one'
            });

            $('.select2-no-search').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose one'
            });

        });
    </script>
@endsection
