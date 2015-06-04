<!-- start: PAGESLIDE LEFT -->
<a class="closedbar inner hidden-sm hidden-xs" href="#">
</a>
<nav id="pageslide-left" class="pageslide inner">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        <div class="main-navigation left-wrapper transition-left">
            <div class="navigation-toggler hidden-sm hidden-xs">
                <a href="#main-navbar" class="sb-toggle-left">
                </a>
            </div>
            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                {foreach from=$menu item=item}
                    <li>
                        <a href="/admin/{$item.Module}"><i class="fa fa-cogs"></i>
                            <span class="title">{$item.Title}</span></a>
                    </li>
                {/foreach}
            </ul>
            <!-- end: MAIN NAVIGATION MENU -->
        </div>
        <!-- end: SIDEBAR -->
    </div>
    <div class="slide-tools">
        <div class="col-xs-6 text-right no-padding">
            <a class="btn btn-sm log-out text-right" href="?action=logout">
                <i class="fa fa-power-off"></i><a href="?action=logout">Atsijungti</a>
            </a>
        </div>
    </div>
</nav>
<!-- end: PAGESLIDE LEFT -->