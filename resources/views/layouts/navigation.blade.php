<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            Marcasite Cursos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Cursos') }}</a>
                </li>

                {{-- Links Admin --}}
                @auth
                    @if(Auth::user()->tipo_usuario == 'Admin')
                        <li class="nav-item dropdown">
                             <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.cursos.*') || request()->routeIs('admin.inscricoes.*') ? 'active' : '' }}" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 Admin
                             </a>
                             <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                 <li><a class="dropdown-item {{ request()->routeIs('admin.cursos.create') ? 'active' : '' }}" href="{{ route('admin.cursos.create') }}">Adicionar Curso</a></li>
                                 {{-- !!! LINK ADICIONADO AQUI !!! --}}
                                 <li><a class="dropdown-item {{ request()->routeIs('admin.cursos.index') || request()->routeIs('admin.cursos.edit') ? 'active' : '' }}" href="{{ route('admin.cursos.index') }}">Gerenciar Cursos</a></li>
                                 <li><a class="dropdown-item {{ request()->routeIs('admin.inscricoes.*') ? 'active' : '' }}" href="{{ route('admin.inscricoes.index') }}">Gerenciar Inscrições</a></li>
                             </ul>
                         </li>
                    @endif
                 @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                             <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                 {{ __('Meu Perfil') }}
                             </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Sair') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>