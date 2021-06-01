@extends('accounting::layouts.layout_2.LTR.layout_navbar_sidebar_fixed')

@section('title', 'Tenant :: Profile :: Edit')

@section('bodyClass', 'sidebar-xs sidebar-opposite-visible')

@section('head')
    {{--<script src="{{ mix('/template/limitless/layout_2/LTR/default/assets/mix/txn.js') }}"></script>--}}
@endsection

@section('sidebar_secondary')
    @include('accounting::settings.sidebar_secondary')
@endsection

@section('content')
    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header" style="border-bottom: 1px solid #ddd;">
            <div class="page-header-content">
                <div class="page-title clearfix">
                    <h1 class="pull-left no-margin text-light">
                        <i class="icon-file-plus position-left"></i> Organisation profile
                        <small>Edit Organisation details</small>
                    </h1>
                    <div class="pull-right">
                        <a href="{{route('organisations.create')}}" class="btn btn-danger pr-20" ><i class="icon-plus22"></i> New Organisation </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- /page header -->

        <!-- Content area -->
        <div class="content">

            @include('limitless.basic_alerts')

            <div class="row mt-20">
                <div class="col-lg-10">

                    <div class="panel panel-flat no-border no-shadow">

                        <div class="panel-body">

                            <!-- Basic legend -->
                            <form class="form-horizontal" action="{{route('organisations.update', $tenant->id)}}" enctype="multipart/form-data" method="post">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="id" value="{{$tenant->id}}" />

                                <input id="logo_selector" type="file" name="logo" class="hidden">

                                        <fieldset>
                                            <!--<legend class="text-semibold">Enter your information</legend>-->

                                            <div class="row">

                                                <div class="col-lg-7">

                                                    {{--
                                                    <div class="form-group">
                                                        <label class="col-lg-2 control-label text-right">Logo</label>
                                                        <div class="col-lg-5">
                                                            <div action="#" class="dropzone mb-10" id="dropzone_single" style="min-height: 40px;">
                                                                @if ($tenant->logo && file_exists(public_path('storage/'.$tenant->logo)))
                                                                    <img src="/timthumb.php?src={{asset('storage/'.$tenant->logo)}}&w=40&h=40&q=100">
                                                                @endif
                                                            </div>
                                                            <input id="logo_selector" type="file" name="logo" class="form-control input-roundless">
                                                        </div>
                                                    </div>
                                                    --}}

                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label text-right">Logo</label>
                                                        <div class="col-lg-8">
                                                            <input id="logo_selector" type="file" name="logo" class="form-control input-roundless">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label text-right">Organization name</label>
                                                        <div class="col-lg-8">
                                                            <input type="text" name="name" value="{{$tenant->name}}" class="form-control input-roundless" placeholder="Client name">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label text-right">Industry</label>
                                                        <div class="col-lg-8">
                                                            <select name="industry" data-placeholder="Select industry" class="select">
                                                                <option></option>
                                                                <option value="information_technology" {{$tenant->industry == 'information_technology' ? 'selected' : ''}}>Information technology</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label text-right">Country</label>
                                                        <div class="col-lg-8">
                                                            <select name="country" data-placeholder="Select country" class="select-search">
                                                                <option></option>
                                                                @foreach ($countries as $country_code => $country_name)
                                                                    <option value="{{$country_code}}" {{$country_code == $tenant->country ? 'selected' : ''}}>{{$country_code}} - {{$country_name}}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-5">
                                                    @if ($tenant->logo && file_exists(public_path('storage/'.$tenant->logo)))
                                                        <img src="/timthumb.php?src={{asset('storage/'.$tenant->logo)}}&w=200&h=200&q=100&zc=2">
                                                    @endif
                                                </div>

                                            </div>

                                            <hr>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Organization address</label>
                                                <div class="col-lg-9">
                                                    <div class="row">
                                                        <div class="col-lg-12 mb-15">
                                                            <input type="text" name="street_line_1" value="{{$tenant->street_line_1}}" class="form-control input-roundless" placeholder="Street" title="Street line 1">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12 mb-15">
                                                            <input type="text" name="street_line_2" value="{{$tenant->street_line_2}}" class="form-control input-roundless" placeholder="Street" title="Street line 2">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-15">
                                                        <div class="col-lg-4">
                                                            <input type="text" name="city" value="{{$tenant->city}}" class="form-control input-roundless" placeholder="City" title="City">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="state_province" value="{{$tenant->state_province}}" class="form-control input-roundless" placeholder="State/Province" title="State/Province">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="zip_postal_code" value="{{$tenant->zip_postal_code}}" class="form-control input-roundless" placeholder="Zip/Postal Code" title="Zip/Postal Code">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-15">
                                                        <div class="col-lg-4">
                                                            <input type="text" name="phone" value="{{$tenant->phone}}" class="form-control input-roundless" placeholder="Phone" title="Phone">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="fax" value="{{$tenant->fax}}" class="form-control input-roundless" placeholder="Fax" title="Fax">
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="website" value="{{$tenant->website}}" class="form-control input-roundless" placeholder="Website" title="Website">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Base currency</label>
                                                <div class="col-lg-5">
                                                    <select name="base_currency" data-placeholder="Select base currency" class="select-search">
                                                        <option></option>
                                                        @foreach ($currencies as $currency_code => $currency_name)
                                                            <option value="{{$currency_code}}" {{ $currency_code == $tenant->base_currency || $currency_code == old('currency') ? 'selected' : ''}}>{{$currency_code}} - {{$currency_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Fiscal Year</label>
                                                <div class="col-lg-5">
                                                    <select name="fiscal_year" data-placeholder="Fiscal Year" class="select">
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
                                                        <option value="December November" {{$tenant->fiscal_year == 'December November' ? 'December November' : ''}}>December November</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Languages</label>
                                                <div class="col-lg-5">
                                                    <select name="language" data-placeholder="Select language" class="select-search">
                                                        <option value="December November" {{$tenant->fiscal_year == 'December November' ? 'December November' : ''}}>December November</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Time Zone</label>
                                                <div class="col-lg-5">
                                                    <select name="time_zone" data-placeholder="Select language" class="select-search">
                                                        @foreach (\DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $time_zone)
                                                            <option value="{{$time_zone}}" {{($time_zone == @$tenant->time_zone) ? 'selected' : ''}}>{{$time_zone}}</option>';
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Date Format</label>
                                                <div class="col-lg-5">
                                                    <select name="date_format" data-placeholder="Select language" class="select">
                                                        <option>yyyy-dd-mm [{{date('Y-m-d')}}]</option>
                                                    </select>
                                                </div>
                                            </div>


                                        </fieldset>

                                        <fieldset>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Company ID</label>
                                                <div class="col-lg-9">

                                                    <div class="row mb-15">
                                                        <div class="col-lg-2">
                                                            <select name="company_id_name" data-placeholder="Select language" class="select">
                                                                <option>Company ID</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="company_id_value" value="{{$tenant->company_id_value}}" class="form-control input-roundless" placeholder="">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label text-right">Tax ID</label>
                                                <div class="col-lg-9">

                                                    <div class="row mb-15">
                                                        <div class="col-lg-2">
                                                            <select name="tax_id_name" data-placeholder="Select language" class="select">
                                                                <option>Tax ID</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="tax_id_value" value="{{$tenant->tax_id_value}}" class="form-control input-roundless" placeholder="">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </fieldset>

                                        <hr/>

                                        <div class="">
                                            <label class="col-lg-2 control-label text-right"> </label>
                                            <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                        </div>

                            </form>
                            <!-- /basic legend  -->

                            <hr style="margin-top: 80px;">
                            <label class="col-lg-2 control-label text-right"> </label>


                        </div>
                    </div>


                </div>
            </div>

        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->
@endsection
