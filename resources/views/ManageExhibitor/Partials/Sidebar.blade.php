<aside class="sidebar sidebar-left sidebar-menu">
    <section class="content">
        <h5 class="heading">Exhibitor Menu</h5>

        <ul id="nav" class="topmenu">
            <li class="{{ Request::is('*dashboard*') ? 'active' : '' }}">
                <a href="{{route('TheExhibitorDashboard')}}">
                    <span class="figure"><i class="ico-home2"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('*events*') ? 'active' : '' }}">
                <a href="{{route('showExhibitorEvents')}}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">Events</span>
                </a>
            </li>

           
        </ul>
    </section>
</aside>
