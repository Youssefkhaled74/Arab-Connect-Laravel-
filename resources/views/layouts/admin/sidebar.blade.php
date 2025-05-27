<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </li>
            <li>
                <a href="{{route('admin/index')}}" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Admins<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/admins/index')}}/0/{{PAGINATION_COUNT}}">Admins</a>
                    </li>
                    <li>
                        <a href="{{route('admin/admins/create')}}">Add Admin</a>
                    </li>
                    {{-- <li>
                        <a href="{{route('admin/admins/archives')}}/0/{{PAGINATION_COUNT}}">Archives</a>
                    </li> --}}
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Users<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/users/index')}}/0/{{PAGINATION_COUNT}}">Users</a>
                    </li>
                    <li>
                        <a href="{{route('admin/users/create')}}">Add User</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Branches<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/branches/index')}}/0/{{PAGINATION_COUNT}}">Branches</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Abouts<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/abouts/index')}}/0/{{PAGINATION_COUNT}}">Abouts</a>
                    </li>
                    <li>
                        <a href="{{route('admin/abouts/create')}}">Add About</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Blogs<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/blogs/index')}}/0/{{PAGINATION_COUNT}}">Blogs</a>
                    </li>
                    <li>
                        <a href="{{route('admin/blogs/create')}}">Add Blog</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Categories<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/categories/index')}}/0/{{PAGINATION_COUNT}}">Categories</a>
                    </li>
                    <li>
                        <a href="{{route('admin/categories/create')}}">Add Category</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Payment Methods<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/paymentMethods/index')}}/0/{{PAGINATION_COUNT}}">Payment Methods</a>
                    </li>
                    <li>
                        <a href="{{route('admin/paymentMethods/create')}}">Add Payment Method</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Contacts<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{route('admin/contacts/index')}}/0/{{PAGINATION_COUNT}}">Contacts</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
