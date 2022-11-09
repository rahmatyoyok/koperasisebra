
<li class="dropdown dropdown-user dropdown-dark">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
        <img alt="" class="img-circle" src="{{ Avatar::create($user->name)->toBase64() }}" />
        <span class="username username-hide-mobile"> {{ $user->name }} </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-default">
        <!-- <li>
            <a href="{{ url('home/profile') }}">
                <i class="icon-user"></i> My Profile </a>
        </li>
        <li class="divider"> </li> -->
        <li>
            <a  target="_blank" href="{{ url('panduan') }}">
                <i class="icon-notebook"></i>Panduan </a>
        </li>
        <li>
            <a href="{{ url('lock') }}">
                <i class="icon-lock"></i> Lock Screen </a>
        </li>
        <li>
            <a href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="icon-key"></i> Log Out
            </a>
            <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">{{ csrf_field() }} </form>
        </li>
    </ul>
</li>
