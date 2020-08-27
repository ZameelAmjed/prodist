<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route("admin.home") }}" class="nav-link">
                   {{-- <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>--}}
                    <img class="nav-icon" src="{{asset('images/dashboard.svg')}}" height="20px">
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("admin.electrician.index") }}" class="nav-link {{ request()->is('admin/electrician') || request()->is('admin/electrician/*') ? 'active' : '' }}">
                    <img class="nav-icon" src="{{asset('images/handyman.svg')}}" height="20px">

                    {{ trans('global.electrician') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("admin.dealers.index") }}" class="nav-link {{ request()->is('admin/dealers') || request()->is('admin/dealers/*') ? 'active' : '' }}">
                    <img class="nav-icon" src="{{asset('images/mover-truck.svg')}}" height="20px">

                    {{ trans('cruds.dealer.title') }}
                </a>
            </li>
            @can('users_manage')
            <li class="nav-item">
                <a href="{{ route("admin.products.index") }}" class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                    {{--<i class="fa-fw fas fa-box nav-icon"></i>--}}
                    <img class="nav-icon" src="{{asset('images/box.svg')}}" height="20px">
                    {{ trans('global.products') }}
                </a>
            </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                       {{-- <i class="fa-fw fas fa-certificate nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/medal.svg')}}" height="20px">
                        {{ trans('global.rewards') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route("admin.rewards.index") }}" class="nav-link pl-4 {{ request()->is('admin/rewards') || request()->is('admin/rewards') ? 'active' : '' }}">
                               {{-- <i class="fa-fw fas fa-plus nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/barcode.svg')}}" height="20px">
                                {{ trans('global.add_rewards') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.payments.index") }}" class="nav-link pl-4 {{ request()->is('admin/payments') || request()->is('admin/payments/*') ? 'active' : '' }}">
                                {{--<i class="fa-fw fas fa-money nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/money.svg')}}" height="20px">
                                {{ trans('global.payments') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.payments.requests") }}" class="nav-link pl-4 {{ request()->is('admin/payments/requests') || request()->is('admin/payments/requests') ? 'active' : '' }}">
                                {{--<i class="fa-fw fas fa-bank nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/request.svg')}}" height="20px">
                                {{ trans('global.payment_request') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.rewards.check") }}" class="nav-link pl-4 {{ request()->is('admin/rewards/check') || request()->is('admin/rewards/check') ? 'active' : '' }}">
                            {{--    <i class="fa-fw fas fa-check-circle nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/tick.svg')}}" height="20px">
                                {{ trans('global.check_rewards') }}
                            </a>
                        </li>
                    </ul>
                </li>
              {{--  <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                  --}}{{--      <i class="fa-fw fas fa-boxes nav-icon"></i>--}}{{--
                        <img class="nav-icon" src="{{asset('images/boxes.svg')}}" height="20px">
                        {{ trans('global.product_management') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route("admin.products.index") }}" class="nav-link pl-4 {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                                --}}{{--<i class="fa-fw fas fa-box nav-icon"></i>--}}{{--
                                <img class="nav-icon" src="{{asset('images/box.svg')}}" height="20px">
                                {{ trans('global.products') }}
                            </a>
                        </li>
                    </ul>
                </li>--}}
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        {{--<i class="fa-fw fas fa-users nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/group.svg')}}" height="20px">
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        {{--@can('super_admin')--}}
                        <li class="nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="nav-link pl-4 {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <img class="nav-icon" src="{{asset('images/security.svg')}}" height="20px">
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="nav-link pl-4 {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                {{--<i class="fa-fw fas fa-briefcase nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/professional.svg')}}" height="20px">
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                        {{--@endcan--}}
                        <li class="nav-item">
                            <a href="{{ route("admin.users.index") }}" class="nav-link pl-4 {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                               {{-- <i class="fa-fw fas fa-user nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/user.svg')}}" height="20px">
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        {{--<i class="fa-fw fas fa-files-o nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/report.svg')}}" height="20px">
                        {{ trans('global.reports') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route("admin.reports.products") }}" class="nav-link pl-4 {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                                {{--<i class="fa-fw fas fa-file nav-icon"></i>--}}
                                <img class="nav-icon" src="{{asset('images/paper.svg')}}" height="20px">
                                {{ trans('global.products') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.reports.electrician") }}" class="nav-link pl-4 {{ request()->is('admin/reports/electrician') || request()->is('admin/reports/electrician') ? 'active' : '' }}">
                                <img class="nav-icon" src="{{asset('images/paper.svg')}}" height="20px">
                                {{ trans('global.electrician') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.reports.dealers") }}" class="nav-link pl-4 {{ request()->is('admin/reports/dealers') || request()->is('admin/reports/dealers') ? 'active' : '' }}">
                                <img class="nav-icon" src="{{asset('images/paper.svg')}}" height="20px">
                                {{ trans('cruds.dealer.title') }}
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <li class="nav-item">
                <a href="{{ route('auth.change_password') }}" class="nav-link">
                    {{--<i class="nav-icon fas fa-fw fa-key"></i>--}}
                    <img class="nav-icon" src="{{asset('images/smart-key.svg')}}" height="20px">
                    Change password
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    {{--<i class="nav-icon fas fa-fw fa-sign-out-alt"></i>--}}
                    <img class="nav-icon" src="{{asset('images/logout.svg')}}" height="20px">
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>