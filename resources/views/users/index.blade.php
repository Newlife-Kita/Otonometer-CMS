@extends('layouts.app')

@section('contents')
    @can('user-show')                    
        <div class="content content-components">
            <div class="container">
                <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Users</li>
                            </ol>
                        </nav>
                        <!-- <h1 class="mg-b-0 tx-spacing--1">Users</h1> -->
                    </div>
                </div>

                @include('flash::message')

                <h4 class="mg-b-10">Users</h4>

                <p class="mg-b-30">
                    This is a list of your <code>Users</code>, you can manage by clicking on action buttons in this table.
                </p>

                <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                    <div>

                    </div>

                    <div class="d-none d-md-block">
                        @can('user-create')
                            <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('users.create') !!}"><i class="fa fa-plus"></i> Add New</a>
                        @endcan
                    </div>
                </div>

                <div class="table-responsive">
                    @include('users.table')
                </div>
            </div>
        </div>
        <!-- /.content -->
    @else    
        <div class="content content-components">
            <div class="container">
                <div class="max-w-xl mx-auto wd-90p">
                    <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                        <div class="px-4 text-18 text-gray-500 border-r border-gray-400 tracking-wider">
                            403                    </div>

                        <div class="ml-4 text-18 text-gray-500 uppercase tracking-wider">
                            This page is unauthorized.                    </div>
                    </div>
                </div>
                <hr class="mg-y-40"> 
            </div>
        </div>                
    @endcan
@endsection

