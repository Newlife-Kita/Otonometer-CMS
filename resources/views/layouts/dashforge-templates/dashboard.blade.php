@extends('layouts.app')

@section('contents')
<div class="content-body">
  <div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Website Analytics</li>
            </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Welcome to Dashboard</h4>
        </div>
    </div>

    <div class="row row-xs">
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <script>
      $(function(){
        'use strict'
      })
    </script>
@endsection