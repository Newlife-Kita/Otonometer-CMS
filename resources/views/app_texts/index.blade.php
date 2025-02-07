@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>

                            @if (!empty($breadcrumb))
                                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('app-text.page') }}">App Texts</a></li>
                                @foreach ($breadcrumb as $crumb)
                                    @if ($loop->last)
                                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                                    @else
                                        <li class="breadcrumb-item" aria-current="page"><a
                                                href="{{ route('app-text.node', $crumb['id']) }}">{{ $crumb['name'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="breadcrumb-item active" aria-current="page">App Texts</li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">App Texts</h4>

            <p class="mg-b-30">
                This is a list of your <code>App Texts</code>, you can manage by clicking on action buttons in this table.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                </div>
            </div>

            <div class="table-responsive">
                @include('app_texts.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
