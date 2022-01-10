<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
        <ul id="sidebarnav" class="pt-4">            
            <li class="sidebar-item">
                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                    <i class="mdi mdi-receipt"></i>
                    <span class="hide-menu">Menu</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                    @can('transaction-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('sales-transaction') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Transaction </span>
                            </a>
                        </li>   
                    @endcan
                    @can('agent-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('agents') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Agents</span>
                            </a>
                        </li>                   
                    @endcan
                    @can('sub_agent-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('subAgents') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Sub Agent </span>
                            </a>
                        </li> 
                    @endcan
                    @can('influencer-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('influencers') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Influencers </span>
                            </a>
                        </li>     
                    @endcan
                    @can('generate_link-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('linkTransactions') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Generate Link Transaction </span>
                            </a>
                        </li>  
                    @endcan
                    @can('role-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('roles') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> Role </span>
                            </a>
                        </li>  
                    @endcan
                    @can('user-list')
                        <li class="sidebar-item">
                            <a href="{{ asset('users') }}" class="sidebar-link">
                                <i class="mdi mdi-note-outline"></i>
                                <span class="hide-menu"> User </span>
                            </a>
                        </li>  
                    @endcan
                </ul>
            </li>    
            
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>