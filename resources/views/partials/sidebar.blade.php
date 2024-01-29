<?php

use Illuminate\Support\Facades\Request;

$user = \Illuminate\Support\Facades\Auth::user();

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.home')}}" style="text-decoration:none">

        <div class="text-center mt-3">
            <h8 style="font-family: Impact;font-size: 20px"><span style="color: #1fc564">Yasir Qadhi</span> DASHBOARD</h8>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @can('dashboard.view')
                    <li class="nav-item">
                        <a href="{{route('admin.home')}}"
                           class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>

                    </li>
                @endcan

                @can('users-view')
                    <li class="nav-header font-weight-bolder">ADMINISTRATION</li>
                    <li class="nav-item">
                        <a href="{{route('admin.users.index')}}"
                           class="nav-link {{ Request::is('admin/users') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-alt"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                @endcan

                @can('role-view')
                    <li class="nav-item">
                        <a href="{{route('admin.roles.index')}}"
                           class="nav-link {{ Request::is('admin/roles') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Roles & Permissions
                            </p>
                        </a>
                    </li>
                @endcan

                    {{-- <li class="nav-header font-weight-bolder">LECTURE</li>
                    <li class="nav-item">
                        <a href="{{route('admin.khudbah_lecture_recitations.index')}}"
                           class="nav-link {{ Request::is('admin/khudbah_lecture_recitations') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Lecture List</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.khatira_lecture_recitations.index')}}"
                           class="nav-link {{ Request::is('admin/khatira_lecture_recitations') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Lecture List</p>
                        </a>
                    </li>


                    <li class="nav-header font-weight-bolder">DUA AND HAMD</li>
                    <li class="nav-item">
                        <a href="{{route('admin.dua_elsabags.index')}}"
                           class="nav-link {{ Request::is('admin/dua_elsabags') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p>Dua List</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.hamd_elsabags.index')}}"
                           class="nav-link {{ Request::is('admin/hamd_elsabags') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p>Hamd & Nath List</p>
                        </a>
                    </li> --}}

                    <li class="nav-header font-weight-bolder">Dua</li>
                    <li class="nav-item">
                        <a href="{{route('admin.dua_list.index')}}"
                           class="nav-link {{ Request::is('admin/dua_list') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Dua List </p>
                        </a>
                    </li>
                    <li class="nav-header font-weight-bolder">Ramadan Series</li>
                    <li class="nav-item">
                        <a href="{{route('admin.ramadan_series.index')}}"
                           class="nav-link {{ Request::is('admin/ramadan_series') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Ramadan Series List </p>
                        </a>
                    </li>
                    <li class="nav-header font-weight-bolder">Stories</li>
                    <li class="nav-item">
                        <a href="{{route('admin.stories.index')}}"
                           class="nav-link {{ Request::is('admin/stories') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Stories List </p>
                        </a>
                    </li>
                    <li class="nav-header font-weight-bolder">Message of Quran</li>
                    <li class="nav-item">
                        <a href="{{route('admin.message_of_qurans.index')}}"
                           class="nav-link {{ Request::is('admin/message_of_qurans') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Message of Quran List </p>
                        </a>
                    </li>




                    <li class="nav-header font-weight-bolder">Lecture</li>
                    <li class="nav-item">
                        <a href="{{route('admin.yasir-lecture.index')}}"
                           class="nav-link {{ Request::is('admin/yasir-lecture') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Lecture List </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.yasir-lecture-category-list.index')}}"
                           class="nav-link {{ Request::is('admin/yasir-lecture-category-list') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Lecture Category List</p>
                        </a>
                    </li>

                    <li class="nav-header font-weight-bolder">Jannah & Jahannam</li>
                    <li class="nav-item">
                        <a href="{{route('admin.jannah-jahannam.index')}}"
                           class="nav-link {{ Request::is('admin/jannah-jahannam') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Jannah & Jahannam </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.jannah-jahannam-category-list.index')}}"
                           class="nav-link {{ Request::is('admin/jannah-jahannam-category-list') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p> Jannah & Jahannam Category List</p>
                        </a>
                    </li>


{{--                @can('customer-list-view')--}}
                    <li class="nav-header font-weight-bolder">CUSTOMER</li>

                    <li class="nav-item">
                        <a href="{{route('admin.customer-detail.index')}}"
                           class="nav-link {{ Request::is('admin/customer-detail') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-astronaut"></i>
                            <p>
                                Customers List
                            </p>
                        </a>
                    </li>

{{--                @if($user->can('banner-view') or $user->can('event-view'))--}}
{{--                    <li class="nav-header font-weight-bolder">OTHERS</li>--}}
{{--                @endif--}}

{{--                    <li class="nav-item">--}}
{{--                        <a href="{{route('admin.tag.index')}}"--}}
{{--                           class="nav-link {{ Request::is('admin/tag') ? 'active' : '' }}">--}}
{{--                            <i class="nav-icon fas fa-tag"></i>--}}
{{--                            <p>--}}
{{--                                Tags--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}

{{--                   --}}
{{--                @can('banner-view')--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{route('admin.banner.index')}}"--}}
{{--                           class="nav-link {{ Request::is('admin/banner') ? 'active' : '' }}">--}}
{{--                            <i class="nav-icon fas fa-image"></i>--}}
{{--                            <p>--}}
{{--                                Message Banner--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endcan--}}

{{--                @can('event-view')--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{route('admin.event.index')}}"--}}
{{--                           class="nav-link {{ Request::is('admin/event') ? 'active' : '' }}">--}}
{{--                            <i class="nav-icon fas fa-calendar"></i>--}}
{{--                            <p>--}}
{{--                                Events--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endcan--}}

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-sign-out" aria-hidden="true"></i>
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </li>


            </ul>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
