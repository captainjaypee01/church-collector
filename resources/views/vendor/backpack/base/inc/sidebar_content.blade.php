<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@hasanyrole('admin|member|leader')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('pledge') }}'><i class='nav-icon la la-question'></i> Pledges</a></li>
@endhasanyrole

@hasanyrole('admin')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('leader') }}'><i class='nav-icon la la-question'></i> Congregational Leaders</a></li>
@endhasanyrole

@hasanyrole('admin|leader')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('member') }}'><i class='nav-icon la la-question'></i> Members</a></li>
@endhasanyrole

@hasanyrole('admin')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('location') }}'><i class='nav-icon la la-question'></i> Locations</a></li>
@endhasanyrole

@hasanyrole('admin|leader')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('collection') }}'><i class='nav-icon la la-question'></i> Projects</a></li>
@endhasanyrole

@hasrole('admin')
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>
@endhasrole
