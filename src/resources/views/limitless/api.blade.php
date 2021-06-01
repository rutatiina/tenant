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
                        <i class="icon-file-plus position-left"></i> API
                        <small>Manage API keys</small>
                    </h1>
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

                            <passport-clients></passport-clients>
                            <passport-authorized-clients></passport-authorized-clients>
                            <passport-personal-access-tokens></passport-personal-access-tokens>

                        </div>
                    </div>


                </div>
            </div>

        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->
@endsection

