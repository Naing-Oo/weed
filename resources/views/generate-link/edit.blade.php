@extends('layouts.admin_master')

@section('title', 'Edit Transaction')

@section('page-title','Transaction')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('linkTransactions') }}">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection
@section('content')

<div class="load-animate animated fadeInUp">
    <div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">Edit Transaction</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('linkTransactions.update', $linkTransaction->id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $linkTransaction->id }}">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label>Agent Id</label>
                        <input type="text" class="form-control" name="sales_id" aria-describedby="agentIdHelp" readonly 
                                value="{{ old('sales_id')? old('sales_id'): $linkTransaction->sales_id }}">
                        @if($errors->has('sales_id'))
                            <small id="agentIdHelp" class="form-text text-danger">{{$errors->first('sales_id')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Agent Name</label>
                        <input type="text" class="form-control" name="seller_name" aria-describedby="agentNameHelp" readonly 
                                value="{{ old('seller_name')? old('seller_name'): $linkTransaction->seller_name }}">
                        @if($errors->has('seller_name'))
                            <small id="agentNameHelp" class="form-text text-danger">{{$errors->first('seller_name')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Product Name</label>
                        <select
                            class="form-select shadow-none"
                            name="product_id"
                            id="product_id"
                            style="width: 100%; height: 36px">
                            <option>Select</option>
                            @foreach ($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <label>Link</label>
                        <input type="text" id="link" class="form-control" name="link" aria-describedby="linkHelp" readonly
                                value="{{ old('product_link')? old('product_link'): $linkTransaction->product_link }}">
                        @if($errors->has('link'))
                            <small id="linkHelp" class="form-text text-danger">{{$errors->first('link')}}</small>
                        @endif
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary"> Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
       $(document).ready(()=>{

            let productId = "{{ $linkTransaction->product_id }}";
            $('#product_id option[value="' + productId + '"]').attr('selected', 'selected');
            getLinkById(productId);

           $('#product_id').change(function(e){
                let id = $(e.currentTarget).val();
                if(id > 0){
                    getLinkById(id);
                }else{
                    $('input[name="link"]').val('');
                }
           })

           function getLinkById(id){
                $.ajax({
                    type: "get",
                    url: "/linkTransactions/get-link-by-product-id/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(link){
                        
                        $salesId = $('input[name="sales_id"]').val();
                        $('input[name="link"]').val(link + '?sales_id=' + $salesId);
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
            
       });
   </script>
@endsection