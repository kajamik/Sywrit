<template>
  <li class="dropdown">
    <a id="notification" href="#" data-toggle="dropdown" title="Notifiche">
      <i class="fa fa-bell" aria-hidden="true" title="Notifiche"></i>
    </a>
    <div class="dropdown-menu">
      <div class="notification-header">
        <div class="notification-title">
          <h3>Notifiche</h3>
          <span class="badge-notification">{{ notifications.length }}</span>
        </div>
        <div class="notification-opts">
          <a href="{{ url('notifications') }}">
            <span class="fa fa-cogs"></span>
          </a>
        </div>
      </div>
      <div class="notification-content">
        <li v-for="notification in notifications">
            <a href="#" v-on:click="MarkAsRead(notification)">
                SomeOne commented on your Post<br>
                <small>{{ notification.data.post.title }}</small>
            </a>
        </li>
        <li v-if="notifications.length == 0">
            There is no new notifications
        </li>
      </div>
    </div>
  </li>
</template>
