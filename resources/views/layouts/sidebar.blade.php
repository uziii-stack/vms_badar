@auth
<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{route('pages.dashboard')}}" class="text-nowrap logo-img">
                <!-- <img src="{{asset('assets/images/logos/dark-logo.svg')}}" width="180" alt="" /> -->
                <img src="{{asset('assets/images/icons/pimec_logo_2025.png')}}" width="180" alt="">
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                @if(session()->get('user')->roles[0]->name != "snseaAdmin" )
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('pages.dashboard')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="admin" )
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('pages.userPanel')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">User Panel</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{route('pages.summaryPanel')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-chart-bar"></i>
                        </div>
                        <span class="hide-menu">Summary</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{route('pages.events')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-calendar-event"></i>
                        </div>
                        <span class="hide-menu">Event Manager</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{route('templates.index')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-template"></i>
                        </div>
                        <span class="hide-menu">Templates</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name == 'bxssUser' || session()->get('user')->roles[0]->name
                =="admin" || session()->get('user')->roles[0]->name
                =="sender" || session()->get('user')->roles[0]->name
                =="authority" || session()->get('user')->roles[0]->name=="printer"||
                session()->get('user')->roles[0]->name=="vendor")
                <li class="sidebar-item">
                    <a href="{{route('pages.organizations')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-building"></i>
                        </div>
                        <span class="hide-menu">Vendor & Staff</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name == 'bxssUser' || session()->get('user')->roles[0]->name
                =="admin" || session()->get('user')->roles[0]->name
                =="sender" || session()->get('user')->roles[0]->name
                =="authority" || session()->get('user')->roles[0]->name=="printer")
                <li class="sidebar-item">
                    <a href="{{route('pages.hrGroups')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-user-circle"></i>
                        </div>
                        <span class="hide-menu">BXSS & Staff</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="admin" || session()->get('user')->roles[0]->name
                =="media" || session()->get('user')->roles[0]->name == 'bxssUser'||
                session()->get('user')->roles[0]->name
                =="sender" || session()->get('user')->roles[0]->name
                =="authority" || session()->get('user')->roles[0]->name=="printer")
                <li class="sidebar-item">
                    <a href="{{route('pages.mediaGroups')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-video"></i>
                        </div>
                        <span class="hide-menu">Media & Staff</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{route('pages.mediaAllStaff')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-camera"></i>
                        </div>
                        <span class="hide-menu">All Media Staff</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="admin" || session()->get('user')->roles[0]->name
                =="depo"|| session()->get('user')->roles[0]->name
                =="sender" || session()->get('user')->roles[0]->name
                =="authority" || session()->get('user')->roles[0]->name=="printer")
                <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-shield-checkered"></i>
                        </div>
                        <span class="hide-menu">Host & Staff</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" href="{{route('pages.depoGroups')}}?status=1">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-point"></i>
                                    </div>
                                    <span class="hide-menu">Active</span>
                                </div>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link justify-content-between" href="{{route('pages.depoGroups')}}?status=0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-point"></i>
                                    </div>
                                    <span class="hide-menu">Inactive</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="orgRep")
                <li class="sidebar-item">
                    <a href="{{route('pages.organization',session()->get('user')->uid)}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-building"></i>
                        </div>
                        <span class="hide-menu">Organization</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="mediaRep" )
                <li class="sidebar-item">
                    <a href="{{route('pages.mediaGroup',session()->get('user')->uid)}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-video"></i>
                        </div>
                        <span class="hide-menu">Media</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="depoRep")
                <li class="sidebar-item">
                    <a href="{{route('pages.depoGroup',session()->get('user')->uid)}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-shield-checkered"></i>
                        </div>
                        <span class="hide-menu">Organization</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="hrRep" )
                <li class="sidebar-item">
                    <a href="{{route('pages.hrGroup',session()->get('user')->uid)}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-video"></i>
                        </div>
                        <span class="hide-menu">BXSS</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="attandeeUser" ||session()->get('user')->roles[0]->name
                =="admin" ||session()->get('user')->roles[0]->name =="bxssUser" ||
                session()->get('user')->roles[0]->name =="depo"|| session()->get('user')->roles[0]->name =="batchUser"||
                session()->get('user')->roles[0]->name =="ncc")
                <li class="sidebar-item">
                    <a href="{{route('pages.attandee')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-user-check"></i>
                        </div>
                        <span class="hide-menu">Attandee</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{route('pages.attendance.attendance')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-user-check"></i>
                        </div>
                        <span class="hide-menu">Attandance</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="dataScan"
                ||session()->get('user')->roles[0]->name=="admin")
                <li class="sidebar-item">
                    <a href="{{route('pages.scan')}}" class="sidebar-link">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="ti ti-scan"></i>
                        </div>
                        <span class="hide-menu">Data Scan</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="temporaryPass"
                ||session()->get('user')->roles[0]->name=="admin")
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('temporaryPass.index')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-id"></i>
                        </span>
                        <span class="hide-menu">Temporary Pass</span>
                    </a>
                </li>
                @endif
                @if(session()->get('user')->roles[0]->name =="admin" ||session()->get('user')->roles[0]->name
                =="snseaAdmin")
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Inland</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('pages.snseaDashboard')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                {{-- <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('pages.programs')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-details"></i>
                        </span>
                        <span class="hide-menu">Program & Coupons</span>
                    </a>
                </li> --}}
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('pages.essentials')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-mist"></i>
                        </span>
                        <span class="hide-menu">Essentials</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('governmentOrganization.index')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-building"></i>
                        </span>
                        <span class="hide-menu">Organization</span>
                    </a>
                </li>
                @endif

            </ul>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
        </nav>

        <!-- End Sidebar navigation -->

    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
@endauth