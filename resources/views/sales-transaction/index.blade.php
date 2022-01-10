@extends('layouts.admin_master')

@section('title', 'Sales Transaction')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sales Transaction</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <h5 class="card-title">Sales Commission Report</h5>
                    <form id="report_frm" method="POST" action="">
                        @csrf()
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Report Type</label>
                                <select
                                    class="form-select shadow-none"
                                    name="selection_type"
                                    id="selection_type"
                                    style="width: 100%; height: 36px">
                                    <option value="">Select</option>
                                        <option value="1">Sales Commission By Date From To</option>
                                        <option value="2">Sales Commission By Month</option>
                                        <option value="3">Sales Commission By Agent</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-2 d-none">
                                <label for="">Date From</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="date_from">
                                </div>
                            </div>
                            <div class="col-lg-2 d-none">
                                <label for="">Date To</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="date_to">
                                </div>
                            </div>
                            <div class="col-lg-2 d-none">
                                <label for="">Month [YYYYMM]</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="month" maxLength="6">
                                </div>
                            </div>
                            <div class="col-lg-4 d-none">
                                <label>Seller</label>
                                <select
                                    class="form-select shadow-none"
                                    name="seller_id"
                                    id="seller_id"
                                    style="width: 100%; height: 36px">
                                       
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <label class="mb-4"></label>
                                <div class="input-group">
                                    <button id="pdf" type="submit" class="btn btn-primary">Print Preview</button>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label class="mb-4"></label>
                                <div class="input-group">
                                    <button id="excel" type="submit" class="btn btn-primary">Export To Excel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h5 class="card-title">Sales Transaction</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Seller Name</th>
                                    <th>Seller Role</th>
                                    <th>Commission Rate</th>
                                    <th>Order Number</th>
                                    <th>Amount</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->role_name }}</td>
                                        <td>{{ $order->commission_rate }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->amount }}</td>
                                        <td>{{ $order->order_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Seller Name</th>
                                    <th>Seller Role</th>
                                    <th>Commission Rate</th>
                                    <th>Order Number</th>
                                    <th>Amount</th>
                                    <th>Order Date</th>
                                </tr>
                            </tfoot>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
       $(document).ready(function(){
            $("#datatable").DataTable();

            $('select[name="selection_type"]').attr('required', 'required');

            $('select[name="selection_type"]').change(function(e){
                $('input[name="date_from"]').parent().parent().addClass('d-none');
                $('input[name="date_to"]').parent().parent().addClass('d-none');
                $('input[name="month"]').parent().parent().addClass('d-none');
                $('select[name="seller_id"]').parent().addClass('d-none');

                let id = $(e.currentTarget).val();

                if(id == "1"){
                    $('input[name="date_from"]').parent().parent().removeClass('d-none');
                    $('input[name="date_to"]').parent().parent().removeClass('d-none');
                    $('input[name="date_from"]').attr('required', 'required');
                    $('input[name="date_to"]').attr('required', 'required');
                }
                else if(id == "2"){
                    $('input[name="month"]').parent().parent().removeClass('d-none');
                    $('input[name="month"]').attr('required', 'required');
                }
                else if(id == "3" | id == "4" | id == "5"){
                    $('input[name="date_from"]').parent().parent().removeClass('d-none');
                    $('input[name="date_to"]').parent().parent().removeClass('d-none');
                    $('select[name="seller_id"]').parent().removeClass('d-none');
                    $('input[name="date_from"]').attr('required', 'required');
                    $('input[name="date_to"]').attr('required', 'required');
                    $('select[name="seller_id"]').attr('required', 'required');
                     reportSelection(id);
                }
                
            }); 

            // success alert
            function swal_success(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: data,
                    showConfirmButton: false,
                    timer: 1000
                })
            }
            // error alert
            function swal_error(error) {
                Swal.fire({
                    position: 'centered',
                    icon: 'error',
                    title: error,
                    showConfirmButton: true,
                })
            }

            let message = '{{session()->get("message")}}';
            if(message != null && message != ''){
                swal_success(message);
            } 

            $('#pdf').click(function(){
                let action = "{{ route('transaction.displayReport') }}";
                $('#report_frm').attr('action', action);
            });

            $('#excel').click(function(){
                let action = "{{ route('transaction.exportExcel') }}";
                $('#report_frm').attr('action', action);
            });

            $('.delete-record').click(function(e){
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteRecord(e);
                    }
                })
            });

            function deleteRecord(e){
                let id = $(e.currentTarget).data('id');
                $.ajax({
                    type: 'DELETE',
                    url: "{{ url('/sales-transaction') }}/" + id,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id
                    },
                    success: function(data){
                        $(e.currentTarget).parent().parent().remove();
                        swal_success(data);
                    },
                    error: function(error){
                        swal_error(error);
                    },
                });
            }


            function reportSelection(id){
                $.ajax({
                    type: "GET",
                    url: "{{ url('/transaction/reportSelection') }}",
                    data: {
                        'id': id
                    },
                    success: function(data){
                        $('select[name="seller_id"]').html("");
                        let index = 0;
                        data.forEach(seller => {
                            let option = "";
                            if(index == 0){
                                option = '<option value="' + seller.sales_id + '">' + seller.full_name + '</option>';
                            }
                            else{
                                option = '<option value="' + seller.sales_id + '">' + seller.full_name + '</option>';
                            }
                            $('select[name="seller_id"]').append(option);
                            index ++;
                        });
                        $('#seller_id').prop('selectedIndex', -1);

                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }

        });

    </script>
@endsection