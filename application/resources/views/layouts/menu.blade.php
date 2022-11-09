@if(!$menu->is_heading)
<li class="nav-item start {{ str_contains(Request::path(), $menu->link) ? 'active open' : '' }}">
    <a href="{{ count($menu->childrens) > 0  ? "javascript:;" : url($menu->link) }}" class="nav-link nav-toggle">
        <i class="{{ $menu->icon }}"></i>
        <span class="title">{{ $menu->name }}</span>
        @if(count($menu->childrens) > 0)
            <span class="arrow"></span>
        @endif
    </a>
    @if(count($menu->childrens) > 0)
        <ul class="sub-menu">
            @foreach ($menu->childrens as $menu)
                @include('layouts.menu', $menu)
            @endforeach
        </ul>
    @endif
</li>
@else
<li class="heading">
    <h3 class="uppercase">{{ $menu->name }}</h3>
</li>
@endif