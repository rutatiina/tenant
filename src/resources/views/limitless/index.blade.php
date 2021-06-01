@extends('accounting::layouts.layout_2.LTR.layout_navbar_sidebar_fixed')

@section('title', 'Organisation(s)')

@section('bodyClass', 'sidebar-xs sidebar-opposite-visible')

@section('head')
    <script src="{{ mix('/mix/tenant.js') }}"></script>
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
                        <i class="icon-file-plus position-left"></i> Organisation(s)
                        <?php /*<small></small>*/ ?>
                    </h1>
                    <div class="pull-right">
                        <a href="{{route('organisations.create')}}" type="button" class="btn btn-danger pr-20"><i class="icon-plus22"></i> New Organisation / Company / Business</a>
                    </div>
                </div>
            </div>

        </div>
        <!-- /page header -->



        <!-- Content area -->
        <div class="content mt-20">


            <div class="row col-lg-8 col-lg-push-2">
                <div class="col-xs-12">

                    @foreach($tenants as $tenant)
                    <div class="panel panel-flat border-left-lg border-left-info">
                        <div class="panel-heading">
                            <h6 class="panel-title"><span class="text-semibold">{{$tenant->name}}</span> </h6>
                        </div>

                        <div class="panel-body">
                            <p>{{$tenant->street_line_1}}</p>
                            <p>{{$tenant->street_line_2}}</p>
                            <hr>
                            <a href="{{route('organisations.edit', $tenant->id)}}" class="btn btn-default btn-rounded"><i class="icon-pencil5 position-left"></i> Edit</a>
                            <a href="{{route('organisations.show', $tenant->id)}}" class="btn btn-default btn-rounded"><i class="icon-profile position-left"></i> Profile</a>
                            <a href="{{route('organisations.switch', $tenant->id)}}" class="btn btn-default btn-rounded"><i class="icon-loop position-left"></i> Switch</a>
                            @if(env('APP_ENV') == 'local')
                            <a href="{{route('organisations.delete-transactions', $tenant->id)}}" class="btn btn-danger btn-rounded rg-ajax-tenant-destroy" data-callback="{{route('organisations.index')}}">
                                <i class="icon-cross2 position-left"></i> Delete all transactions
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>


        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->

@endsection
