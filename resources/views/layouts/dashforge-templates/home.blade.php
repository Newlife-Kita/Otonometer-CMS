@extends('layouts.app')

@section('contents')
<div class="content content-fixed content-auth-alt">
  <div class="container ht-100p tx-center">
    
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
      <div></div>
      <div class="">
        <button type="button" class="btn btn-sm pd-x-15 btn-white btn-uppercase mg-l-5 btn-clear-cache"><i data-feather="trash" class="wd-10 mg-r-5"></i> Clear Cache</button>
      </div>
    </div>

    <div class="ht-100p d-flex flex-column align-items-center justify-content-center">
      <h1 class="tx-color-01 mg-t-50 tx-36 tx-sm-32 tx-lg-64 mg-xl-b-5">Content Management System</h1>
      <div class="wd-90p wd-lg-500 mg-b-15"><img src="{{ asset('otonometer.png') }}" class="img-fluid" alt=""></div>
      <div class="mg-t-30 wd-70p wd-lg-200 mg-b-15"><img src="{{ asset('neraca_ruang.png') }}" class="img-fluid" alt=""></div>
    </div>
  </div><!-- container -->
</div><!-- content -->
@endsection

@section('scripts')
    <script>
      $(function(){
        'use strict'
        $(document).on('click','.btn-clear-cache', function(){
          Swal.fire({
            title: "Yakin hapus cache?",
            text: "Apakah Anda ingin melanjutkan",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Yes, Clear cache",
          }).then((result) => {
            if (result.isConfirmed) {
              location.href="{{ route('clear-cache') }}";
            }
          });
        })
      })
    </script>
@endsection