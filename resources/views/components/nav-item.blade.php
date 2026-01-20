<li>
    <a href="{{ route($route ?? '#') }}" 
       class="flex items-center gap-3 px-4 py-2.5 text-slate-300 hover:bg-slate-800 rounded-lg transition-colors {{ request()->routeIs($route . '*') ? 'bg-slate-800 text-white' : '' }}">
        <i class="fa-solid {{ $icon }} w-5"></i>
        <span>{{ $label }}</span>
    </a>
</li>
