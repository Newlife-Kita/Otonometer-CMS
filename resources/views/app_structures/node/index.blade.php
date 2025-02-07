@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('app-structure.page') }}">App Structures</a></li>
                            @foreach ($breadcrumb as $crumb)
                                @if ($loop->last)
                                    <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                                @else
                                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('app-structure.node', $crumb['id']) }}">{{ $crumb['name'] }}</a></li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Struktur Aplikasi - Node atau Divisi</h4>

            <p class="mg-b-30">
                Berikut ini adalah list Node, Divisi, Atau Bagian yang terdapat pada
                {{ $parent->type == 'page' ? 'Halaman' : 'Node' }} {{ @$parent->name }}.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                    @can('appStructure-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('app-structure.node.create', @$parent->id) !!}"><i
                                class="fa fa-plus"></i> Add New</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                @include('app_structures.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
