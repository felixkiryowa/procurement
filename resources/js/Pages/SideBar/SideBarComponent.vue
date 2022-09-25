<template>

  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item" v-if="user.name === 'Company'">
            <a class="nav-link" href="/manage/procurement/plans">
                <i class="ti-marker menu-icon"></i>
              <span class="menu-title">Procurement Plans</span>
            </a>
          </li>
          <li class="nav-item" v-if="user.name === 'Company'">
            <a class="nav-link" href="/manage/bid/invitations">
                <i class="ti-marker menu-icon"></i>
              <span class="menu-title">Bid Invitations</span>
            </a>
          </li>
          <li class="nav-item" v-if="user.name === 'Company'">
            <a class="nav-link" href="/manage/company/users">
                <i class="icon-head menu-icon"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
      <li class="nav-item" v-if="user.name === 'Company'">
        <a class="nav-link" href="/manage/company/users">
          <i class="ti-user menu-icon"></i>
          <span class="menu-title">Users</span>
        </a>
      </li>
      <li class="nav-item" v-if="user.name === 'Super Systems Administrator'">
        <a class="nav-link" href="/manage/companies">
          <i class="ti-marker menu-icon"></i>
          <span class="menu-title">Registered Companies</span>
        </a>
      </li>
      <!-- <li class="nav-item" v-if="user.name === 'Super Systems Administrator'">
        <a class="nav-link" href="/manage/all/company/users">
          <i class="ti-user menu-icon"></i>
          <span class="menu-title">Company Users</span>
        </a>
      </li> -->
      <li class="nav-item" v-if="user.name === 'Super Systems Administrator'">
        <a class="nav-link" href="/manage/service/providers">
          <i class="ti-marker menu-icon"></i>
          <span class="menu-title">Registered Providers</span>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "SideBarComponent",
  props: {
    user: Object,
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
