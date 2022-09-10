<template>
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
        <li class="nav-item" >
            <a class="nav-link" href="/manage/procurement/plans">
                <i class="icon-arrow menu-icon"></i>
              <span class="menu-title">Procurement Plans</span>
            </a>
          </li>
          <li class="nav-item" >
            <a class="nav-link" href="/manage/company/users">
                <i class="icon-head menu-icon"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
        </ul>
      </nav>
</template>

<script>
    export default {
      name:"SideBarComponent",
      props: {
          user:Object
      },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
