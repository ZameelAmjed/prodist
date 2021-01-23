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
            @can('users_manage')
                <li class="nav-item">
                    <a href="{{ route("admin.stores.index") }}"
                       class="nav-link {{ request()->is('admin/stores') || request()->is('admin/stores/*') ? 'active' : '' }}">
                        <img class="nav-icon" src="{{asset('images/store.svg')}}" height="20px">
                        {{ trans('global.stores') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.suppliers.index") }}"
                       class="nav-link {{ request()->is('admin/suppliers') || request()->is('admin/suppliers/*') ? 'active' : '' }}">
                        <img class="nav-icon" src="{{asset('images/supplier.svg')}}" height="20px">
                        {{ trans('global.suppliers') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.products.index") }}"
                       class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                        {{--<i class="fa-fw fas fa-box nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/box.svg')}}" height="20px">
                        {{ trans('global.products') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.orders.index") }}"
                       class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}">
                        {{--<i class="fa-fw fas fa-box nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/orders.svg')}}" height="20px">
                        {{ trans('cruds.order.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.payment.index") }}"
                       class="nav-link {{ request()->is('admin/payment') || request()->is('admin/payment/*') ? 'active' : '' }}">
                        {{--<i class="fa-fw fas fa-box nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/money.svg')}}" height="20px">
                        {{ trans('cruds.payments.title') }}
                    </a>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        {{--<i class="fa-fw fas fa-users nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/inventory.svg')}}" height="20px">
                        Inventory
                    </a>
                    <ul class="nav-dropdown-items">
                        {{--@can('super_admin')--}}
                        <li class="nav-item">
                            <a href="{{ route("admin.purchase_orders.index") }}"
                               class="nav-link pl-4 {{ request()->is('purchase_orders') || request()->is('purchase_orders') ? 'active' : '' }}">
                                <img class="nav-icon-sub" src="{{asset('images/circle.svg')}}">
                                Purchase Orders
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        {{--<i class="fa-fw fas fa-users nav-icon"></i>--}}
                        <img class="nav-icon" src="{{asset('images/group.svg')}}" height="20px">
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        {{--@can('super_admin')--}}
                        <li class="nav-item">
                            <a href="{{ route("admin.permissions.index") }}"
                               class="nav-link pl-4 {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <img class="nav-icon-sub" src="{{asset('images/circle.svg')}}" height="20px">
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route("admin.roles.index") }}"
                               class="nav-link pl-4 {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                {{--<i class="fa-fw fas fa-briefcase nav-icon"></i>--}}
                                <img class="nav-icon-sub" src="{{asset('images/circle.svg')}}" height="20px">
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                        {{--@endcan--}}
                        <li class="nav-item">
                            <a href="{{ route("admin.users.index") }}"
                               class="nav-link pl-4 {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                {{-- <i class="fa-fw fas fa-user nav-icon"></i>--}}
                                <img class="nav-icon-sub" src="{{asset('images/circle.svg')}}" height="20px">
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.reports.index')}}">
                        <img class="nav-icon" src="{{asset('images/report.svg')}}" height="20px">
                        {{ trans('global.reports') }}
                    </a>
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
                <a href="#" class="nav-link"
                   onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    {{--<i class="nav-icon fas fa-fw fa-sign-out-alt"></i>--}}
                    <img class="nav-icon" src="{{asset('images/logout.svg')}}" height="20px">
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>