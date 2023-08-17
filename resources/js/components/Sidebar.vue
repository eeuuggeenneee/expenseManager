<template>
    <div class="sidebar" :class="{ 'mini-sidebar': isMiniSidebar }">
        <ul class="menu list-unstyled">
            <li class="menu-item text-white">
                <a class="" href="/">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>

            <template v-if="authUserId === 1">
                <li class="menu-item text-white">
                    <span class="menu-label">   <i class="fa fa-user"></i> User Management</span>
             
                    <div></div>
                    <ul class="submenu list-unstyled">
                        <li class="submenu-item">
                            <a href="/roles"> Roles </a>
                        </li>
                        <li class="submenu-item"><a href="/users">User</a></li>
                    </ul>
                </li>
            </template>

            <li class="menu-item text-white">
                <span class="menu-label">    <i class="fa fa-credit-card"></i> Expense Management</span>

                <ul class="submenu list-unstyled">
                    <template v-if="authUserId === 1">
                        <li class="submenu-item">
                            <a href="/expense_category">Expense Category</a>
                        </li>
                    </template>
                    <li class="submenu-item">
                        <a href="/expenses">Expenses</a>
                    </li>
                </ul>
            </li>

            <change-component></change-component>
        </ul>

        <!-- Mini Sidebar Open Button -->
   
        <div class="mini-sidebar-open" @click="toggleMiniSidebar"><i class="fa-solid fa-arrow-right"></i></div>
        <div class="mini-sidebar-close" v-if="isMiniSidebar" @click="toggleMiniSidebar"><i class="fa-solid fa-arrow-left"></i></div>
        <!-- Mini Sidebar Close Button -->
        

    </div>
</template>


<script>
import axios from "axios";

export default {
  name: "SidebarComponent",
  data() {
    return {
      authUserId: null,
      isMiniSidebar: false,
    };
  },
  async mounted() {
    try {
      let response = await axios.get("/api/auth-user");
      this.authUserId = response.data.user_id;
    } catch (error) {
      console.error("Failed to fetch auth user data:", error);
    }

    window.addEventListener("resize", this.handleWindowResize);
    this.handleWindowResize();
  },
  methods: {
    toggleMiniSidebar() {
      this.isMiniSidebar = !this.isMiniSidebar;
    },
    handleWindowResize() {
      this.isMiniSidebar = window.innerWidth <= 1000;
    },
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleWindowResize);
  },
};
</script>

<style scoped>
.sidebar {
    padding-top: 1.5%;
    width: 220px;
    height: calc(100vh - 50px);
    position: fixed;
    top: 55px;
    left: 0;
    background-color: #333;
    overflow-y: auto;
    z-index: 1;
    
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}
.mini-sidebar .menu-label {
    display: none;
}
.sidebar ul li {
    padding: 8px;
    text-align: left;
}

.sidebar ul li a {
    color: white;
    display: block;
    transition: 0.3s;
    text-decoration: none;
}

.sidebar ul li a:hover {
    background-color: #575757;
}

.sidebar ul ul {
    padding-left: 20px;
}

.mini-sidebar {
    width: 30px;
    height: 50px;
    top: auto;    
    bottom: 0;      
}

.mini-sidebar-open {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
    background-color: #333;
    color: white;
    cursor: pointer;
    padding: 5px;
    display: none;
}

.mini-sidebar:hover .mini-sidebar-open {
    display: block;
}

.mini-sidebar:not(:hover) .mini-sidebar-open {
    display: block;
}

.mini-sidebar .mini-sidebar-close {
    display: none;
}

.mini-sidebar:hover .mini-sidebar-close {
    display: block;
}

.mini-sidebar .menu-item > a > i {
    margin-right: 0;
}

.mini-sidebar .menu-item > a:not(.fa):not(.submenu-item) {
    display: none;
}

.mini-sidebar .submenu-item a {
    display: none;
}
.mini-sidebar .mini-sidebar-open {
    display: none;
}

.mini-sidebar:hover .mini-sidebar-open {
    display: none;
}

.mini-sidebar .mini-sidebar-close {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
    background-color: #333;
    color: white;
    cursor: pointer;
    padding: 5px;
    display: none;
}

.mini-sidebar:hover .mini-sidebar-close {
    display: block;
}
:not(.mini-sidebar) .mini-sidebar-open {
    display: block;
}


.mini-sidebar .menu-item > a:not(:hover):not(.fa) {
    display: none;
}

.mini-sidebar .menu-item > a:not(.fa):not(.submenu-item) {
    display: none;
}

.mini-sidebar .submenu-item a {
    display: none;
}


.mini-sidebar .menu-item > a:not(:hover):not(.fa) {
    display: none;
}


:not(.mini-sidebar) .mini-sidebar-close {
    display: none;
}

</style>
