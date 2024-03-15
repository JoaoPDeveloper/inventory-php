@extends('include.master')

@section('title','Inventário | Faturamento')

@section('page-title','Faturamento')

@section('content')


<div class="row clearfix">

    <create-invoice :categorys="{{ $category }}" :customers="{{ $customer }}"></create-invoice>

</div>


<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
            
                <h2 style="visibility: hidden;">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-stock">
                        Nova Fatura
                    </button>
                </h2>
            </div>

            <view-invoice :categorys="{{ $category }}" :customers="{{ $customer }}"></view-invoice>

        </div>
    </div>
</div>




@endsection

@push('script')

<script type="text/javascript" src="{{ url('public/js/invoice.js') }}"></script>

@endpush