<!-- ========== App Menu ========== -->
<div class="navbar-menu">

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span><?php echo app('translator')->get('translation.menu'); ?></span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="javascript:atras()">
                        <i data-feather="arrow-left" ></i> <span>Atras</span>
                    </a>
                </li> <!-- end Atras -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="index">
                        <i data-feather="home" ></i> <span>Inicio</span>
                    </a>
                </li> <!-- end Inicio -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i data-feather="users" ></i> <span>Clubes</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLayouts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/clubes-catalogo" class="nav-link">Catalogo de clubes</a>
                            </li>
                            <?php if(Auth::user()->rol == "administrador"): ?>
                                <li class="nav-item">
                                    <a href="/apps-clubes-admin" class="nav-link">Administrar mis clubes</a>
                                </li>
                            <?php elseif(Auth::user()->rol == "super"): ?>
                                <li class="nav-item">
                                    <a href="/apps-clubes-admin" class="nav-link">Administrar todos los clubes</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li> <!-- end Clubes  -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebara" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApps">
                        <i data-feather="calendar" ></i> <span>Eventos</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebara">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <?php if(Auth::user()->rol == "administrador" || Auth::user()->rol == "super"): ?>
                                    <a href="/apps-calendar" class="nav-link">Calendario de eventos</a>
                                <?php else: ?>
                                    <a href="/apps-calendario-participante" class="nav-link">Calendario de eventos</a>
                                <?php endif; ?>
                            </li>
                            <?php if(Auth::user()->rol == "administrador" || Auth::user()->rol == "super"): ?>
                                <li class="nav-item">
                                    <a href="/apps-eventos-list?club=all" class="nav-link">Todos mis eventos</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>  <!-- end Eventos -->

                <?php if(Auth::user()->rol == "colaborador"): ?>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="asistencias">
                            <i data-feather="check-square" ></i> <span>Asistencias</span>
                        </a>
                    </li> <!-- end Asistencias -->
                <?php endif; ?>

                <?php if(Auth::user()->rol != "colaborador"): ?>
                    <li class="nav-item">
                        <?php if(Auth::user()->rol == "super"): ?>
                            <a class="nav-link menu-link" href="apps-crm-users">
                                <i data-feather="user" ></i> <span>Usuarios registrados</span>
                            </a>
                        <?php else: ?>
                            <a class="nav-link menu-link" href="pages-team?club=all">
                                <i data-feather="user" ></i> <span>Participantes registrados</span>
                            </a>
                        <?php endif; ?>
                    </li> <!-- end Usuarios -->
                    <li class="nav-item">
                        <?php if(Auth::user()->rol == "super"): ?>
                            <a class="nav-link menu-link" href="super-dashboard">
                                <i data-feather="bar-chart" ></i> <span>Estadisticas</span>
                            </a>
                        <?php else: ?>
                            <a class="nav-link menu-link" href="admin-dashboard">
                                <i data-feather="bar-chart" ></i> <span>Estadísticas</span>
                            </a>
                        <?php endif; ?>
                    </li> <!-- end Estadisticas -->
                <?php endif; ?>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>