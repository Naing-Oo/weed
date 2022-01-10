@extends('layouts.admin_master')

@section('title', 'New Transaction')

@section('page-title','Transaction')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('salesTransactionHeaders.index') }}">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="load-animate animated fadeInUp">
    <div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">New Transaction</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('salesTransactionHeaders.store') }}">
                @csrf

                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label>Transaction Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="transaction_number" placeholder="Transaction Number" aria-describedby="transactionNumberHelp" value="{{ old('transaction_number') }}">
                        </div>
                        @if($errors->has('transaction_number'))
                            <small id="transactionNumberHelp" class="form-text text-danger">{{$errors->first('transaction_number')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Transaction Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="sales_date" placeholder="mm/dd/yyyy" aria-describedby="salesDateHelp" value="{{ old('sales_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text h-100">
                                    <i class="mdi mdi-calendar"></i>
                                </span>
                            </div>
                        </div>
                        @if($errors->has('sales_date'))
                            <small id="salesDateHelp" class="form-text text-danger">{{$errors->first('sales_date')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Level</label>
                        <select
                            class="form-select shadow-none"
                            name="level_id"
                            id="level_id"
                            style="width: 100%; height: 36px">
                            <option>Select</option>
                                <option value="1">Agent</option>
                                <option value="2">Downline</option>
                                <option value="3">Influencer</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label id="lavel_name">Choose Level First</label> {{-- option --}}
                        <select
                            class="form-select shadow-none"
                            name="agent_id"
                            id="agent_id"
                            style="width: 100%; height: 36px">
                            <option id="select_name">Select</option>
                        </select>
                    </div>
                    <div class="col-lg-8 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Address" aria-describedby="addressHelp" value="{{ old('address') }}">
                    @if($errors->has('address'))
                            <small id="addressNameHelp" class="form-text text-danger">{{$errors->first('address')}}</small>
                    @endif
                </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <label for="curr_code" class="form-label">Currency Code</label>
                        <input type="text" class="form-control" name="curr_code" placeholder="Currency Code" aria-describedby="currCodeHelp" value="{{ old('curr_code') }}">
                        @if($errors->has('curr_code'))
                            <small id="currCodeHelp" class="form-text text-danger">{{$errors->first('curr_code')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label for="curr_rate" class="form-label">Currency Rate</label>
                        <input type="number" class="form-control" name="curr_rate" placeholder="Currency Rate" aria-describedby="currRateHelp" value="{{ old('curr_rate') }}">
                        @if($errors->has('curr_rate'))
                            <small id="currRateHelp" class="form-text text-danger">{{$errors->first('curr_rate')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label for="sales_total_amount" class="form-label">Total Amount</label>
                        <input type="number" class="form-control" name="sales_total_amount" placeholder="Total Amount" aria-describedby="salesTotalAmountHelp" value="{{ old('sales_total_amount') }}">
                        @if($errors->has('sales_total_amount'))
                            <small id="salesTotalAmountHelp" class="form-text text-danger">{{$errors->first('sales_total_amount')}}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 mb-3">
                        <button class="btn btn-danger delete" id="removeRows" type="button">- Delete Line</button>
                        <button class="btn btn-success" id="addRows" type="button">+ Add Line</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <table id="transaction-item" class="table table-striped table-bordered">
                            <tr>
                                <div class="row">
                                    <th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
                                    <th width="20%">Item Name</th>
                                    <th width="40%">Description</th>
                                    <th width="10%">Quantity</th>
                                    <th width="13%">Price</th>
                                    <th width="15%">Total</th>
                                </div>
                            </tr>
                            <tr>
                                <td><input class="itemRow mt-2" type="checkbox"></td>
                                <td><input type="text" name="itemName[]" id="itemName_1" class="form-control" autocomplete="off"></td>
                                <td><input type="text" name="description[]" id="description_1" class="form-control" autocomplete="off"></td>
                                <td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off"></td>
                                <td><input type="number" name="price[]" id="price_1" class="form-control price" autocomplete="off"></td>
                                <td><input type="number" name="total[]" id="total_1" value="" class="form-control total" autocomplete="off"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                
            <!-- <div class="clearfix"></div>
            </div> -->
                <button type="submit" class="btn btn-primary">Save Transaction</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
       $(document).ready(()=>{

            $('input[name="sales_date"]').datepicker({
                autoclose: true,
                todayHighlight: true,
            });

            $('#level_id').change(function(e){
                let id = $(e.currentTarget).val();
                if(id == 1){
                    $('#lavel_name').html('Agent Name');
                }
                else if(id == 2){
                    $('#lavel_name').html('Downline Name');
                }
                else if(id == 3){
                    $('#lavel_name').html('Influencer Name');
                }
                
                getAgentByLevel(id);
            });

            $('#agent_id').change(function(e){
                let id = $(e.currentTarget).val();
                let levelId = $('select[name="level_id"]').val();
                getAddress(id, levelId);
            });

            function getAgentByLevel(id){
                $.ajax({
                    type: 'GET',
                    url: "/salesTransactionHeaders/get-agent-by-level/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data){
                        $('select[name="agent_id"]').html("");
                        $('input[name="address"]').val('');
                        
                        data.forEach(agent => {
                            let option = '<option value="' + agent.id + '">' + agent.full_name + '</option>';
                            $('select[name="agent_id"]').append(option);
                        });
                        $('#agent_id').prop('selectedIndex', -1);
                        let oldAgentId = "{{ old('agent_id') }}";
                        let agentId = oldAgentId? oldAgentId: '';
                        $('#agent_id option[value="' + agentId + '"]').attr('selected', 'selected');
                    },
                    error: function(error){
                        console.log(error);
                    }

                });
            }

            function getAddress(id, levelId){
                $.ajax({
                    type: 'GET',
                    url: "/salesTransactionHeaders/get-address/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id,
                        'levelId': levelId
                    },
                    success: function(data){
                       $('input[name="address"]').val(data.address);
                    },
                    error: function(error){
                        console.log(error);
                    }

                });
            }

            //  checkAll
            $(document).on('click', '#checkAll', function() {          	
                $(".itemRow").prop("checked", this.checked);
            });	
            $(document).on('click', '.itemRow', function() {  	
                if ($('.itemRow:checked').length == $('.itemRow').length) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
            });
           // Add Rows
            var count = $(".itemRow").length;
            $(document).on('click', '#addRows', function() { 
                count++;
                var htmlRows = '';
                htmlRows += '<tr>';
                htmlRows += '<td><input class="itemRow mt-2" type="checkbox"></td>';          
                htmlRows += '<td><input type="text" name="itemName[]" id="itemName_'+count+'" class="form-control" autocomplete="off"></td>';          
                htmlRows += '<td><input type="text" name="description[]" id="description_'+count+'" class="form-control" autocomplete="off"></td>';	
                htmlRows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity" autocomplete="off"></td>';   		
                htmlRows += '<td><input type="number" name="price[]" id="price_'+count+'" class="form-control price" autocomplete="off"></td>';		 
                htmlRows += '<td><input type="number" name="total[]" id="total_'+count+'" class="form-control total" autocomplete="off"></td>';          
                htmlRows += '</tr>';
                $('#transaction-item').append(htmlRows);
            }); 
            // Remove Row
            $(document).on('click', '#removeRows', function(){
                $(".itemRow:checked").each(function() {
                    $(this).closest('tr').remove();
                });
                $('#checkAll').prop('checked', false);
                calculateTotal();
	        });	

            $(document).on('blur', "[id^=quantity_]", function(){
                calculateTotal();
            });
            
            $(document).on('blur', "[id^=price_]", function(){
                calculateTotal();
            });
            
            $(document).on('blur', "#taxRate", function(){		
                calculateTotal();
            });

            // calculate function
            function calculateTotal(){
                var totalAmount = 0; 
                $("[id^='price_']").each(function() {
                    var id = $(this).attr('id');
                    id = id.replace("price_",'');
                    var price = $('#price_'+id).val();
                    var quantity  = $('#quantity_'+id).val();
                    if(!quantity) {
                        quantity = 1;
                    }
                    var total = price*quantity;
                    $('#total_'+id).val(parseFloat(total));
                    totalAmount += total;			
                });

                $('input[name="sales_total_amount"]').val(parseFloat(totalAmount));	
                // var taxRate = $("#taxRate").val();
                // var subTotal = $('#subTotal').val();	
                // if(subTotal) {
                //     var taxAmount = subTotal*taxRate/100;
                //     $('#taxAmount').val(taxAmount);
                //     subTotal = parseFloat(subTotal)+parseFloat(taxAmount);
                //     $('#totalAftertax').val(subTotal);		
                //     var amountPaid = $('#amountPaid').val();
                //     var totalAftertax = $('#totalAftertax').val();	
                //     if(amountPaid && totalAftertax) {
                //         totalAftertax = totalAftertax-amountPaid;			
                //         $('#amountDue').val(totalAftertax);
                //     } else {		
                //         $('#amountDue').val(subTotal);
                //     }
                // }
            }
       });
   </script>
@endsection