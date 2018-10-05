<aside class="sidebar sidebar-left sidebar-menu">
    <section class="content">
        <h5 class="heading">Attendee Menu</h5>

        <ul id="nav" class="topmenu">
            <li class="{{ Request::is('*dashboard*') ? 'active' : '' }}">
                <a href="{{route('showAttendeeDashboard', array('attendee_id' => $attendee->id))}}">
                    <span class="figure"><i class="ico-home2"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
