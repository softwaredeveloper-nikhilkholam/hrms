<?php $username = Auth::user()->username; ?>
<div class="sidebar-inner slimscroll">
<hr>

    <div id="sidebar-menu" class="sidebar-menu">

        <ul>
            <li class="submenu">
                <a href="javascript:void(0);">
                    <i class="bi bi-briefcase-fill text-primary"></i> 
                    <span>Master</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li><a href="/CRM/masterchecklist">Task</a></li>
                    <li><a href="/CRM/assignTaskSheet/assignTaskList">Assign Task</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);">
                    <i class="bi bi-calendar-check-fill text-success"></i>
                    <span>Daily Work</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li><a href="/CRM/dailyTaskList">Checklist</a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span>Extra Task</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li><a href="/CRM/extraTask/raiseTask">Raised Task</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>