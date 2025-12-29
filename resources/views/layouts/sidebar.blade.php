<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i data-feather="home"></i>
                    <span data-key="t-dashboard">Dashboard</span>
                </a>
            </li>
            @can('Configuration_menu')
            <li class="menu-title" data-key="t-menu">Configuration</li>
            @endcan
            @can('Configuration_add_user')
            <li>
                <a href="{{ route('user.index') }}">
                    <i data-feather="users"></i>
                    <span>Manage User</span>
                </a>
            </li>
            @endcan
            @can('Configuration_dropdown')
            <li>
                <a href="{{ route('dropdown.index') }}">
                    <i class="mdi mdi-chevron-down-box"></i>
                    <span>Manage Dropdown</span>
                </a>
            </li>
            @endcan

            @can('Configuration_master_data')
            <li class="menu-title" data-key="t-menu">Master Data</li>
            @endcan

            @can('Configuration_Business_Entities')
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="mdi mdi-domain"></i>
                    <span>Business Entities</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('company.index') }}">
                            <i class="mdi mdi-office-building"></i>
                            <span>Master Company</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('department/*') || request()->is('bagian/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('department.index') }}">
                            <i class="mdi mdi-graph-outline"></i>
                            <span>Master Department</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('employee/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('employee.index') }}">
                            <i class="mdi mdi-account-group"></i>
                            <span>Master Employee</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('salesman.index') }}">
                            <i class="mdi mdi-account-tie"></i>
                            <span>Master Salesman</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('province.index') }}">
                            <i class="mdi mdi-map-marker"></i>
                            <span>Master Province</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('country.index') }}">
                            <i class="mdi mdi-wan"></i>
                            <span>Master Country</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('costcenter.index') }}">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span>Master Cost Center</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('customer/*') || request()->is('customeraddress/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('customer.index') }}">
                            <i class="mdi mdi-account-switch"></i>
                            <span>Master Customer</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('supplier/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('supplier.index') }}">
                            <i class="mdi mdi-inbox-arrow-down"></i>
                            <span>Master Supplier</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Configuration_Operational')
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="mdi mdi-wrench"></i>
                    <span>Operational / Assets</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li class="{{ request()->is('processproduction/*') || request()->is('workcenter/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('processproduction.index') }}">
                            <i class="mdi mdi-cogs"></i>
                            <span>Process Production</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('group.index') }}">
                            <i class="mdi mdi-google-circles-group"></i>
                            <span>Master Group</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('groupsub.index') }}">
                            <i class="mdi mdi-lan"></i>
                            <span>Master Group Sub</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('unit.index') }}">
                            <i class="mdi mdi-camera-control"></i>
                            <span>Master Unit</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('waste.index') }}">
                            <i class="mdi mdi-recycle"></i>
                            <span>Master Waste</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('downtime.index') }}">
                            <i class="mdi mdi-package-down"></i>
                            <span>Master Downtime</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('warehouse.index') }}">
                            <i class="mdi mdi-warehouse"></i>
                            <span>Master Warehose</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vehicle.index') }}">
                            <i class="mdi mdi-rv-truck"></i>
                            <span>Master Vehicle</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('Configuration_Productions')
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="mdi mdi-cog-play"></i>
                    <span>Productions</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('rawmaterial.index') }}">
                            <i class="mdi mdi-apps-box"></i>
                            <span>Raw Material</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('wip/*') || request()->is('wiprefwip/*') || request()->is('wipref/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('wip.index') }}">
                            <i class="mdi mdi-cog-box"></i>
                            <span>Master WIP</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('fg/*') || request()->is('fgref/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('fg.index') }}">
                            <i class="mdi mdi-check-network"></i>
                            <span>Master Product FG</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sparepart.index') }}">
                            <i class="mdi mdi-archive"></i>
                            <span>Sparepart & Aux.</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('historystock/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('historystock.rm') }}">
                            <i class="mdi mdi-clipboard-text-clock"></i>
                            <span>History Stocks</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('Configuration_Financial_Aspects')
            <li>
                <a href="javascript: void(0);" class="has-arrow">
                    <i class="mdi mdi-cash"></i>
                    <span>Financial Aspects</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('termpayment.index') }}">
                            <i class="mdi mdi-file-alert"></i>
                            <span>Master Term Payment</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('currency.index') }}">
                            <i class="mdi mdi-credit-card-marker"></i>
                            <span>Master Currency</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reason.index') }}">
                            <i class="mdi mdi-file-question"></i>
                            <span>Master Reason</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('approval.index') }}">
                            <i class="mdi mdi-check-decagram"></i>
                            <span>Master Approval</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            @can('Configuration_Audit_Logs')
            <li class="menu-title" data-key="t-menu">Logs</li>
            <li>
                <a href="{{ route('auditlog') }}">
                    <i class="mdi mdi-chart-donut"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            @endcan

        </ul>
    </div>
    <!-- Sidebar -->
</div>