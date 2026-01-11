    <!-- Search Modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-transparent">
                <div class="card shadow-none mb-0">
                    <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                        <i class="ti ti-search fs-22"></i>
                        <input type="search" class="form-control border-0" placeholder="Search">
                        <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x fs-22"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidenav Menu Start -->
    <div class="sidebar" id="sidebar">

        <!-- Start Logo -->
        <div class="sidebar-logo">
            <div>
                <!-- Logo Normal -->
                <a href="{{url('home')}}" class="logo logo-normal">
                    <img src="{{URL::asset('build/img/logo.svg')}}" alt="Logo">
                </a>

                <!-- Logo Small -->
                <a href="{{url('home')}}" class="logo-small">
                    <img src="{{URL::asset('build/img/logo-small.svg')}}" alt="Logo">
                </a>

                <!-- Logo Dark -->
                <a href="{{url('home')}}" class="dark-logo">
                    <img src="{{URL::asset('build/img/logo-white.svg')}}" alt="Logo">
                </a>
            </div>
            <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
                <i class="ti ti-arrow-bar-to-left"></i>
            </button>

            <!-- Sidebar Menu Close -->
            <button class="sidebar-close">
                <i class="ti ti-x align-middle"></i>
            </button>
        </div>
        <!-- End Logo -->

        <!-- Sidenav Menu -->
        <div class="sidebar-inner" data-simplebar>
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="menu-title"><span>Main Menu</span></li>
                    <li>
                        <ul>
                            <li class=" {{ Request::is('home') ? 'active' : '' }}">
                                <a href="{{url('home')}}" ><i class="ti ti-home"></i><span>Home</span></a>
                            </li>
                        </ul>
                    </li>
                    @can('dashboard_view')
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('index', '/','leads-dashboard','project-dashboard') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-dashboard"></i><span>Dashboard</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{url('dashboard')}}" class="{{ Request::is('dashboard', '/') ? 'active' : '' }}">Telemarketing</a></li>
                                    <li><a href="{{url('customers-dashboard')}}" class="{{ Request::is('customers-dashboard') ? 'active' : '' }}">Customer</a></li>
                                    <!-- <li><a href="{{url('prospects-dashboard')}}" class="{{ Request::is('prospects-dashboard') ? 'active' : '' }}">Prospects</a></li> -->
                                    <li><a href="{{url('products-dashboard')}}" class="{{ Request::is('products-dashboard') ? 'active' : '' }}">Products</a></li>
                                    <li><a href="{{url('sales-dashboard')}}" class="{{ Request::is('sales-dashboard') ? 'active' : '' }}">Sales</a></li>
                                    <li><a href="{{url('employees-dashboard')}}" class="{{ Request::is('employees-dashboard') ? 'active' : '' }}">Employee Analysis</a></li>
                                    <li><a href="{{url('crm-dashboard')}}" class="{{ Request::is('crm-dashboard') ? 'active' : '' }}">CRM Analysis</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @canany(["common_type_view", "common_merk_view", "common_payment_method_view", "common_position_view"])
                    <li class="menu-title"><span>COMMON</span></li>
                    <li>
                        <ul>
                            @can("common_type_view")
                            <li class=" {{ Request::is('common-type*') ? 'active' : '' }}">
                                <a href="{{url('common-type')}}" ><i class="ti ti-settings-cog"></i><span>Type</span></a>
                            </li>
                            @endcan
                            @can("common_merk_view")
                            <li class=" {{ Request::is('common-merk*') ? 'active' : '' }}">
                                <a href="{{url('common-merk')}}" ><i class="ti ti-settings-cog"></i><span>Merk</span></a>
                            </li>
                            @endcan
                            @can("common_payment_method_view")
                            <li class=" {{ Request::is('common-payment-method*') ? 'active' : '' }}">
                                <a href="{{url('common-payment-method')}}" ><i class="ti ti-settings-cog"></i><span>Payment Method</span></a>
                            </li>
                            @endcan
                            @can("common_position_view")
                            <li class=" {{ Request::is('common-position*') ? 'active' : '' }}">
                                <a href="{{url('common-position')}}" ><i class="ti ti-settings-cog"></i><span>Position</span></a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    @canany(["ref_compaign_view", "ref_commodity_view", "ref_regional_view"])
                    <li class="menu-title"><span>REFERENCE</span></li>
                    <li>
                        <ul>
                            @can("ref_compaign_view")
                            <li class=" {{ Request::is('ref-compign*') ? 'active' : '' }}">
                                <a href="{{url('ref-compign')}}" ><i class="ti ti-settings-cog"></i><span>Campaigns</span></a>
                            </li>
                            @endcan
                            @can("ref_commodity_view")
                            <li class=" {{ Request::is('ref-commodity*') ? 'active' : '' }}">
                                <a href="{{url('ref-commodity')}}" ><i class="ti ti-settings-cog"></i><span>Commodity</span></a>
                            </li>
                            @endcan
                            @can("ref_regional_view")
                            <li class=" {{ Request::is('regional*') ? 'active' : '' }}">
                                <a href="{{url('regional')}}" ><i class="ti ti-map-pin"></i><span>Regional Data</span></a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    @canany(["employees_view"])
                    <li class="menu-title"><span>HR</span></li>
                    <li>
                        <ul>
                            @can("employees_view")
                            <li class="{{ Request::is('employees*') ? 'active' : '' }}">
                                <a href="{{ url('employees') }}"><i class="ti ti-users"></i><span>Employees</span></a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    @canany(["telemarketing_create", "telemarketing_view"])
                    <li class="menu-title"><span>TELEMARKETING</span></li>
                    <li>
                        <ul>
                            @can("telemarketing_create")
                            <li class="{{ Request::is('surveys/create') ? 'active' : '' }}">
                                <a href="{{ url('surveys/create') }}"><i class="ti ti-forms"></i><span>New Survey</span></a>
                            </li>
                            @endcan
                            @can("telemarketing_admin")
                            <li class="{{ Request::is('admin-surveys/create') ? 'active' : '' }}">
                                <a href="{{ url('admin-surveys/create') }}"><i class="ti ti-forms"></i><span>Admin Survey</span></a>
                            </li>
                            @endcan
                            @can("telemarketing_view")
                            <li class="{{ Request::is('surveys') ? 'active' : '' }}">
                                <a href="{{ url('surveys') }}"><i class="ti ti-list-check"></i><span>Contact</span></a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    @canany(["contacts_view", "prospects_view", "customers_view", "products_view", "sales_view", "reminders_view", "followups_view", "activities_view"])
                    <li class="menu-title"><span>CRM</span></li>
                    <li>
                        <ul>
                            @can("prospects_view")
                            <li class="{{ Request::is('prospects') ? 'active' : '' }}" href="{{ url('prospects') }}">
                                <a href="{{url('prospects')}}"><i class="ti ti-timeline-event-exclamation"></i><span>Prospects</span></a>
                            </li>
                            @endcan
                            @can("customers_view")
                            <li class="{{ Request::is('customers', 'customers-list', 'customers-details') ? 'active' : '' }}">
                                <a href="{{url('customers')}}"><i class="ti ti-brand-campaignmonitor"></i><span>Customers</span></a>
                            </li>
                            @endcan
                            @can("products_view")
                            <li class="{{ Request::is('products', 'product-details', 'products-list') ? 'active' : '' }}">
                                <a href="{{url('products')}}"><i class="ti ti-atom-2"></i><span>Products</span></a>
                            </li>
                            @endcan
                            @can("sales_view")
                            <li class="{{ Request::is('sales', 'sales-list', 'sales-sync') ? 'active' : '' }}">
                                <a href="{{url('sales')}}"><i class="ti ti-list-check"></i><span>Sales</span></a>
                            </li>
                            @endcan
                            <li class="{{ Request::is('delivery-schedule*') ? 'active' : '' }}">
                                <a href="{{ route('delivery-schedule.index') }}"><i class="ti ti-truck"></i><span>Delivery Schedule</span></a>
                            </li>
                            <li class="{{ Request::is('approvals*') ? 'active' : '' }}">
                                <a href="{{ route('approvals.index') }}"><i class="ti ti-check-box"></i><span>Approval Center</span></a>
                            </li>
                            @can("reminders_view")
                            <li class="{{ Request::is('reminders', 'reminders-list', 'reminder-details') ? 'active' : '' }}">
                                <a href="{{url('reminders')}}"><i class="ti ti-file-star"></i><span>Reminder</span></a>
                            </li>
                            @endcan
                            @can("followups_view")
                            <li class="{{ Request::is('followups', 'followups-list', 'followup-details') ? 'active' : '' }}">
                                <a href="{{url('followups')}}"><i class="ti ti-file-check"></i><span>Follow-Up</span></a>
                            </li>
                            @endcan
                            @can("activities_view")
                            <li class="{{ Request::is('activities', 'activity-calls', 'activity-mail', 'activity-meeting', 'activity-task') ? 'active' : '' }}">
                                <a href="{{url('activities')}}"><i class="ti ti-bounce-right"></i><span>Activities</span></a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    <li class="menu-title"><span>Reports</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="{{ Request::is('sales-reports', 'sales-delivery-reports') ? 'subdrop active' : '' }}">
                                    <i class="ti ti-report-analytics"></i><span>Reports</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a class="{{ Request::is('sales-reports') ? 'active' : '' }}"
                                            href="{{ url('sales-reports') }}">Sales Reports</a></li>
                                    <li><a class="{{ Request::is('sales-delivery-reports') ? 'active' : '' }}"
                                            href="{{ url('sales-delivery-reports') }}">Sales Delivery Reports</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>User Management</span></li>
                    <li>
                        <ul>
                            <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{url('users')}}"><i class="ti ti-users"></i><span>Manage Users</span></a></li>
                            <li class="{{ Request::is('roles') ? 'active' : '' }}"><a href="{{url('roles')}}"><i class="ti ti-user-shield"></i><span>Roles</span></a></li>
                            <li class="{{ Request::is('permission-subjects') ? 'active' : '' }}"><a href="{{url('permission-subjects')}}"><i class="ti ti-folder"></i><span>Permission Subjects</span></a></li>
                            <li class="{{ Request::is('permissions') ? 'active' : '' }}"><a href="{{url('permissions')}}"><i class="ti ti-shield"></i><span>Permissions</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <!-- Sidenav Menu End -->
