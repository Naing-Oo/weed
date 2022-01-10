<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Agent;
use App\Models\SubAgent;
use App\Models\Influencer;
use Carbon\Carbon;
use ExcelReport;
use Validator;

class ExportExcelController extends Controller
{
    public function exportExcel(Request $request){
           
        $selectionType = $request->selection_type;

        if($selectionType == "1"){

            $fromDate = $request->input('date_from');
            $toDate = $request->input('date_to');

            $title = 'Sales Commision By Date';

            $meta = [
                'Order Date' => "From " . Carbon::parse($fromDate)->format('d M Y') . ' To ' . Carbon::parse($toDate)->format('d M Y')
                // 'Sort By' => $fromDate
            ];

             $agent = DB::table('orders')->select( 	
                                'sellers.name as name', 
                                'agents.full_name as agent_name', 
                                DB::raw('"" as sub_agent_name'),                                    
                                'sellers.role_name as role',
                                'sellers.sales_id as sales_id',
                                'sellers.commission_rate as commission_amt',
                                'orders.order_number as order_number',
                                'orders.order_date as order_date',
                                'orders.amount as base_amount',
                                'sellers.commission_rate as agent',
                                DB::raw('0 as sub_agent'),
                                DB::raw('0 as influencer'),
                                DB::raw('orders.amount - sellers.commission_rate as company')
                            )                             
                                ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                                ->leftJoin('agents', 'agents.sales_id', 'sellers.sales_id')
                                ->where('sellers.role_name', 'Agent')
                                ->whereBetween('orders.order_date', [$fromDate, $toDate]);
            $subAgent = DB::table('orders')->select( 	
                                'sellers.name as name', 
                                DB::raw('(select agents.full_name from agents where agents.id = sub_agents.agent_id) as agent_name'),
                                'sub_agents.full_name as sub_agent_name',
                                'sellers.role_name as role',
                                'sellers.sales_id as sales_id',
                                'sellers.commission_rate as commission_amt',
                                'orders.order_number as order_number',
                                'orders.order_date as order_date',
                                'orders.amount as base_amount',
                                DB::raw('(select agents.commission_rate - sellers.commission_rate from agents where agents.id = sub_agents.agent_id) as agent'),
                                'sellers.commission_rate as sub_agent',
                                DB::raw('0 as influencer'),
                                DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = sub_agents.agent_id) as company')
                            )                       
                                ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                                ->leftJoin('sub_agents', 'sub_agents.sales_id', 'sellers.sales_id')
                                ->where('sellers.role_name', 'Sub Agent')
                                ->whereBetween('orders.order_date', [$fromDate, $toDate])
                                ->unionAll($agent);
            $influencer = DB::table('orders')->select(
                                'sellers.name as name', 
                                DB::raw('(select agents.full_name from agents where agents.id = influencers.agent_id) as agent_name'),
                                DB::raw('(select sub_agents.full_name from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent_name'),
                                'sellers.role_name as role',
                                'sellers.sales_id as sales_id',
                                'sellers.commission_rate as commission_amt',
                                'orders.order_number as order_number',
                                'orders.order_date as order_date',
                                'orders.amount as base_amount',
                                DB::raw('(select agents.commission_rate - (sellers.commission_rate + (select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id)) from agents where agents.id = influencers.agent_id) as agent'),
                                DB::raw('(select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent'),
                                'sellers.commission_rate as influencer',
                                DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = influencers.agent_id) as company')
                            )
                            ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                            ->leftJoin('influencers', 'influencers.sales_id', 'sellers.sales_id')
                            ->where('sellers.role_name', 'Influencer')
                            ->whereBetween('orders.order_date', [$fromDate, $toDate])
                            ->unionAll($subAgent)
                            ->orderBy('agent_name', 'asc')
                            ->orderBy('sub_agent_name', 'asc');


            $columns = [ 
                      'Name' => 'name', 
                      'Agent' => 'agent_name', 
                      'Sub Agent' => 'sub_agent_name',                                    
                      'Role' => 'role',
                      'Order Date' => 'order_date',
                      'Base Amt' => 'base_amount',
                      'Agent Amt' => 'agent',
                      'Sub Agent Amt' => 'sub_agent',
                      'Influencer Amt' => 'influencer',
                      'Company Amt' => 'company' 
            ];


            return ExcelReport::of($title, $meta, $influencer, $columns)
                            ->editColumn('Order Date', [ 
                                'displayAs' => function($result) {
                                    return Carbon::parse($result->order_date)->format('d M Y');
                                },
                                'class' => 'left'
                            ])
                            ->editColumns(['Base Amt', 'Agent Amt', 'Sub Agent Amt', 'Influencer Amt', 'Company Amt'], [ 
                                'class' => 'right'
                            ])
                            ->groupBy(['Agent','Sub Agent']) 
                            ->showTotal([
                                'Base Amt' => 'point',
                                'Agent Amt' => 'point',
                                'Sub Agent Amt' => 'point',
                                'Influencer Amt' => 'point',
                                'Company Amt' => 'point'
                            ])
                            ->download($title . " " . Carbon::parse($fromDate)->format('d M Y') . ' To ' . Carbon::parse($toDate)->format('d M Y'));
                                                           
                            

        }elseif ($selectionType == "2") {

            $month = $request->input('month');

            $title = 'Sales Commision By Month';

            $meta = [
                'Order Month' => Carbon::parse($month)->format('F Y')
            ];

            $agent = DB::table('orders')->select( 	
                            'sellers.name as name', 
                            'agents.full_name as agent_name', 
                            DB::raw('"" as sub_agent_name'),                                    
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            'sellers.commission_rate as agent',
                            DB::raw('0 as sub_agent'),
                            DB::raw('0 as influencer'),
                            DB::raw('orders.amount - sellers.commission_rate as company')
                        )                             
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('agents', 'agents.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Agent')
                        ->where('orders.order_month', $month);
            $subAgent = DB::table('orders')->select( 	
                            'sellers.name as name', 
                            DB::raw('(select agents.full_name from agents where agents.id = sub_agents.agent_id) as agent_name'),
                            'sub_agents.full_name as sub_agent_name',
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            DB::raw('(select agents.commission_rate - sellers.commission_rate from agents where agents.id = sub_agents.agent_id) as agent'),
                            'sellers.commission_rate as sub_agent',
                            DB::raw('0 as influencer'),
                            DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = sub_agents.agent_id) as company')
                        )                       
                            ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                            ->leftJoin('sub_agents', 'sub_agents.sales_id', 'sellers.sales_id')
                            ->where('sellers.role_name', 'Sub Agent')
                            ->where('orders.order_month', $month)
                            ->unionAll($agent);
            $influencer = DB::table('orders')->select(
                            'sellers.name as name', 
                            DB::raw('(select agents.full_name from agents where agents.id = influencers.agent_id) as agent_name'),
                            DB::raw('(select sub_agents.full_name from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent_name'),
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            DB::raw('(select agents.commission_rate - (sellers.commission_rate + (select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id)) from agents where agents.id = influencers.agent_id) as agent'),
                            DB::raw('(select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent'),
                            'sellers.commission_rate as influencer',
                            DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = influencers.agent_id) as company')
                        )
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('influencers', 'influencers.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Influencer')
                        ->unionAll($subAgent)
                        ->where('orders.order_month', $month)
                        ->orderBy('agent_name', 'asc')
                        ->orderBy('sub_agent_name', 'asc');


            $columns = [ 
                'Name' => 'name', 
                'Agent' => 'agent_name', 
                'Sub Agent' => 'sub_agent_name',                                    
                'Role' => 'role',
                'Order Date' => 'order_date',
                'Base Amt' => 'base_amount',
                'Agent Amt' => 'agent',
                'Sub Agent Amt' => 'sub_agent',
                'Influencer Amt' => 'influencer',
                'Company Amt' => 'company' 
            ];


            return ExcelReport::of($title, $meta, $influencer, $columns)
                        ->editColumn('Order Date', [ 
                            'displayAs' => function($result) {
                                return Carbon::parse($result->order_date)->format('d M Y');
                            },
                            'class' => 'left'
                        ])
                        ->editColumns(['Base Amt', 'Agent Amt', 'Sub Agent Amt', 'Influencer Amt', 'Company Amt'], [ 
                            'class' => 'right'
                        ])
                        ->groupBy(['Agent', 'Sub Agent']) 
                        ->showTotal([
                            'Base Amt' => 'point',
                            'Agent Amt' => 'point',
                            'Sub Agent Amt' => 'point',
                            'Influencer Amt' => 'point',
                            'Company Amt' => 'point'
                        ])                                              
                        ->download($title . " " . Carbon::parse($month)->format('F Y'));

        }elseif($selectionType == "3"){

            $fromDate = $request->input('date_from');
            $toDate = $request->input('date_to');
            $sellerId = $request->input('seller_id');
            $agentName = DB::table('sellers')->where('sales_id', $sellerId)->first()->name;

            $title = 'Sales Commision By Agent';

            $meta = [
                'Order Date' => Carbon::parse($fromDate)->format('d M Y') . ' To ' . Carbon::parse($toDate)->format('d M Y'),
                 'Agent' => $agentName
            ];
            
            $agent = DB::table('orders')->select( 	
                            'sellers.name as name', 
                            'agents.full_name as agent_name', 
                            DB::raw('"" as sub_agent_name'),                                    
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            'sellers.commission_rate as agent',
                            DB::raw('0 as sub_agent'),
                            DB::raw('0 as influencer'),
                            DB::raw('orders.amount - sellers.commission_rate as company')
                        )                             
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('agents', 'agents.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Agent')
                        ->whereBetween('orders.order_date', [$fromDate, $toDate])
                        ->where('sellers.sales_id', $sellerId);
            $subAgent = DB::table('orders')->select( 	
                            'sellers.name as name', 
                            DB::raw('(select agents.full_name from agents where agents.id = sub_agents.agent_id) as agent_name'),
                            'sellers.name as sub_agent_name',
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            DB::raw('(select agents.commission_rate - sellers.commission_rate from agents where agents.id = sub_agents.agent_id) as agent'),
                            'sellers.commission_rate as sub_agent',
                            DB::raw('0 as influencer'),
                            DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = sub_agents.agent_id) as company')
                        )                       
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('sub_agents', 'sub_agents.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Sub Agent')
                        ->whereBetween('orders.order_date', [$fromDate, $toDate])
                        ->where(DB::raw('(select agents.sales_id from agents where agents.id = sub_agents.agent_id)'), $sellerId)
                        ->unionAll($agent);
            $influencer = DB::table('orders')->select(
                            'sellers.name as name', 
                            DB::raw('(select agents.full_name from agents where agents.id = influencers.agent_id) as agent_name'),
                            DB::raw('(select sub_agents.full_name from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent_name'),
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            DB::raw('(select agents.commission_rate - (sellers.commission_rate + (select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id)) from agents where agents.id = influencers.agent_id) as agent'),
                            DB::raw('(select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent'),
                            'sellers.commission_rate as influencer',
                            DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = influencers.agent_id) as company')
                        )
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('influencers', 'influencers.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Influencer')
                        ->unionAll($subAgent)
                        ->whereBetween('orders.order_date', [$fromDate, $toDate])
                        ->where(DB::raw('(select agents.sales_id from agents where agents.id = influencers.agent_id)'), $sellerId)
                        ->orderBy('agent_name', 'asc')
                        ->orderBy('sub_agent_name', 'asc');

            $columns = [ 
                'Name' => 'name', 
                'Agent' => 'agent_name', 
                'Sub Agent' => 'sub_agent_name',                                    
                'Role' => 'role',
                'Order Date' => 'order_date',
                'Base Amt' => 'base_amount',
                'Agent Amt' => 'agent',
                'Sub Agent Amt' => 'sub_agent',
                'Influencer Amt' => 'influencer',
                'Company Amt' => 'company' 
            ];


            return ExcelReport::of($title, $meta, $influencer, $columns)
                        ->editColumn('Order Date', [ 
                            'displayAs' => function($result) {
                                return Carbon::parse($result->order_date)->format('d M Y');
                            },
                            'class' => 'left'
                        ])
                        ->editColumns(['Base Amt', 'Agent Amt', 'Sub Agent Amt', 'Influencer Amt', 'Company Amt'], [ 
                            'class' => 'right'
                        ])
                        ->groupBy(['Agent', 'Sub Agent']) 
                        ->showTotal([
                            'Base Amt' => 'point',
                            'Agent Amt' => 'point',
                            'Sub Agent Amt' => 'point',
                            'Influencer Amt' => 'point',
                            'Company Amt' => 'point'
                        ])
                        ->download("Sales Commission By ". $agentName . " " . Carbon::parse($fromDate)->format('d M Y') . ' To ' . Carbon::parse($toDate)->format('d M Y'));

        }elseif($selectionType == "4"){

            $fromDate = $request->input('date_from');
            $toDate = $request->input('date_to');
            $sellerId = $request->input('seller_id');

            $title = 'Sales Commision By Sub Agent';

            $meta = [
                'Order Date' => Carbon::parse($fromDate)->format('d M Y') . ' To ' . Carbon::parse($toDate)->format('d M Y'),
                'Sub Agent' => DB::table('sellers')->where('sales_id', $sellerId)->first()->name
            ];
            
            $subAgent = DB::table('orders')->select( 	
                            'sellers.name as name', 
                            DB::raw('(select agents.full_name from agents where agents.id = sub_agents.agent_id) as agent_name'),
                            'sellers.name as sub_agent_name',
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            DB::raw('(select agents.commission_rate - sellers.commission_rate from agents where agents.id = sub_agents.agent_id) as agent'),
                            'sellers.commission_rate as sub_agent',
                            DB::raw('0 as influencer'),
                            DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = sub_agents.agent_id) as company')
                        )                       
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('sub_agents', 'sub_agents.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Sub Agent')
                        ->whereBetween('orders.order_date', [$fromDate, $toDate])
                        ->where('sellers.sales_id', $sellerId);
            $influencer = DB::table('orders')->select(
                            'sellers.name as name', 
                            DB::raw('(select agents.full_name from agents where agents.id = influencers.agent_id) as agent_name'),
                            DB::raw('(select sub_agents.full_name from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent_name'),
                            'sellers.role_name as role',
                            'sellers.sales_id as sales_id',
                            'sellers.commission_rate as commission_amt',
                            'orders.order_number as order_number',
                            'orders.order_date as order_date',
                            'orders.amount as base_amount',
                            DB::raw('(select agents.commission_rate - (sellers.commission_rate + (select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id)) from agents where agents.id = influencers.agent_id) as agent'),
                            DB::raw('(select sub_agents.commission_rate from sub_agents where sub_agents.id = influencers.sub_agent_id) as sub_agent'),
                            'sellers.commission_rate as influencer',
                            DB::raw('orders.amount - (select agents.commission_rate from agents where agents.id = influencers.agent_id) as company')
                        )
                        ->leftJoin('sellers', 'orders.seller_id', 'sellers.sales_id')
                        ->leftJoin('influencers', 'influencers.sales_id', 'sellers.sales_id')
                        ->where('sellers.role_name', 'Influencer')
                        ->unionAll($subAgent)
                        ->whereBetween('orders.order_date', [$fromDate, $toDate])
                        ->where(DB::raw('(select sub_agents.sales_id from sub_agents where sub_agents.id = influencers.sub_agent_id)'), $sellerId)
                        ->orderBy('agent_name', 'asc')
                        ->orderBy('sub_agent_name', 'asc');

            $columns = [ 
                'Name' => 'name', 
                'Agent' => 'agent_name', 
                'Sub Agent' => 'sub_agent_name',                                    
                'Role' => 'role',
                'Order Date' => 'order_date',
                'Base Amt' => 'base_amount',
                'Agent Amt' => 'agent',
                'Sub Agent Amt' => 'sub_agent',
                'Influencer Amt' => 'influencer',
                'Company Amt' => 'company' 
            ];


            return ExcelReport::of($title, $meta, $influencer, $columns)
                        ->editColumn('Order Date', [ 
                            'displayAs' => function($result) {
                                return Carbon::parse($result->order_date)->format('d M Y');
                            },
                            'class' => 'left'
                        ])
                        ->editColumns(['Base Amt', 'Agent Amt', 'Sub Agent Amt', 'Influencer Amt', 'Company Amt'], [ 
                            'class' => 'right'
                        ])
                        ->groupBy('Sub Agent') 
                        ->showTotal([
                            'Base Amt' => 'point',
                            'Agent Amt' => 'point',
                            'Sub Agent Amt' => 'point',
                            'Influencer Amt' => 'point',
                            'Company Amt' => 'point'
                        ])
                        ->download($title);
        
        }elseif($selectionType == "5"){

            $fromDate = $request->input('date_from');
            $toDate = $request->input('date_to');
            $sellerId = $request->input('seller_id');
            // $sortBy = $request->input('sort_by');

            $title = 'Sales Commision By Agent';

            $meta = [
                'Order Date' => Carbon::parse($fromDate)->format('d M Y') . ' To ' . Carbon::parse($toDate)->format('d M Y')
                // 'Sort By' => $sortBy
            ];
            
            if($sellerId == 'all'){
                $queryBuilder = DB::table('orders')->select([
                    'sellers.name',
                    'orders.order_date',
                    'orders.amount',
                    'sellers.commission_rate',
                    DB::raw('orders.amount - sellers.commission_rate as total_amt')
                    
                ])
                ->leftJoin('sellers', 'sellers.sales_id', 'orders.seller_id')
                ->whereBetween('orders.order_date', [$fromDate, $toDate]);
                // ->orderBy('order_date', 'desc');
            }else{
                $queryBuilder = DB::table('orders')->select([
                    'sellers.name',
                    'orders.order_date',
                    'orders.amount',
                    'sellers.commission_rate',
                    DB::raw('orders.amount - sellers.commission_rate as total_amt')
                    
                ])
                ->leftJoin('sellers', 'sellers.sales_id', 'orders.seller_id')
                ->whereBetween('orders.order_date', [$fromDate, $toDate])
                ->where('sellers.sales_id', $sellerId);
            }
        

            $columns = [ 
                'Seller Name' => 'name',
                'Order Date' => 'order_date',
                'Base Amount' => 'amount', 
                'Commission Amt' => 'commission_rate',
                'Total Amt' => 'total_amt', 
            ];

            return PdfReport::of($title, $meta, $queryBuilder, $columns)
                            ->editColumn('Order Date', [ 
                                'displayAs' => function($result) {
                                    return Carbon::parse($result->order_date)->format('d M Y');
                                },
                                'class' => 'left'
                            ])
                            ->editColumns(['Base Amount', 'Total Amt', 'Commission Amt'], [ 
                                'class' => 'right bold'
                            ])
                            ->groupBy('Seller Name') 
                            ->showTotal([
                                'Total Amt' => 'point'
                            ])
                            ->showTotal([ 
                                'Base Amount' => 'point',
                                'Commission Amt' => 'point',
                                'Total Amt' => 'point'
                            ])                        
                            ->limit(20) 
                            ->stream();
        }
     
    }
    
}
