@auth
<!--begin::Aside menu-->
<div class="aside-menu flex-column-fluid">
    <!--begin::Aside Menu-->
    <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
        data-kt-scroll-offset="0">
        <!--begin::Menu-->
        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
            id="#kt_aside_menu" data-kt-menu="true">
            @foreach ($menuSide as $item)
            @switch ($item['type'])
            @case('section')
            <div class="menu-item">
                <div class="menu-content {{ $item['menu_css'] ?? '' }} pb-2">
                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ $item['title'] }}</span>
                </div>
            </div>
            @break
            @case('separator')
            <div class="menu-item">
                <div class="menu-content">
                    <div class="separator mx-1 my-4"></div>
                </div>
            </div>
            @break
            @default
            @if (isset($item['children']))
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $item['menu_css'] ?? '' }}">
                <span class="menu-link">
                    <span class="menu-icon">
                        @if (isset($item['icon']))
                        <span class="svg-icon svg-icon-2">
                            {!! $item['icon'] !!}
                        </span>
                        @endif
                    </span>
                    <span class="menu-title">{{ $item['title'] }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg {{ $item['menu_css'] ?? '' }}">
                    @foreach ($item['children'] as $childLevel1)
                    @if (isset($childLevel1['children']))
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ $childLevel1['menu_css'] ?? '' }}">
                        <span class="menu-link">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{ $childLevel1['title'] }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion menu-active-bg {{ $childLevel1['menu_css'] ?? '' }}">
                            @foreach ($childLevel1['children'] as $childLevel2)
                            <div class="menu-item">
                                <a class="menu-link {{ $childLevel2['menu_css'] ?? '' }}"
                                    href="{{ $childLevel2['route'] }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ $childLevel2['title'] }}</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link {{ $childLevel1['menu_css'] ?? '' }}"
                                href="{{ $childLevel1['route'] }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ $childLevel1['title'] }}</span>
                            </a>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @else
            <div class="menu-item">
                <a class="menu-link {{ $item['menu_css'] ?? '' }}" href="{{ $item['route'] }}">
                    @if (isset($item['icon']))
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            {!! $item['icon'] !!}
                        </span>
                    </span>
                    @endif
                    <span class="menu-title">{{ $item['title'] }}</span>
                </a>
            </div>
            @endif

            @break
            @endswitch

            @endforeach
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Aside Menu-->
</div>
<!--end::Aside menu-->
@endauth