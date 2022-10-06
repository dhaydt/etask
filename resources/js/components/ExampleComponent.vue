<template>
    <div class="container">
        <Header></Header>
        <div class="nav-menu d-flex justify-content-end align-items-center">
            <button
                type="button"
                class="btn btn-primary rotate user-btn py-0 px-2"
                data-kt-menu-trigger="hover"
                data-kt-menu-placement="bottom-start"
                data-kt-menu-offset="0px, 5px"
            >
                <img height="20" src="img/user.png" />Welcome, {{ user.name }}
                <i class="fas fa-caret-down ms-2"></i>
            </button>

            <div
                class="menu menu-user menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                data-kt-menu="true"
            >
                <div class="separator mb-3 opacity-75"></div>
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3"> New Ticket </a>
                </div>
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3"> New Customer </a>
                </div>
                <div class="menu-item px-3">
                    <form method="POST" action="/logout">
                        <input type="hidden" name="_token" :value="csrf" />
                        <a
                            href="javascript:"
                            type="submit"
                            class="menu-link px-3"
                        >
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            Keluar
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <Body
            :todos="todos"
            :doing="doing"
            :done="done"
            :staffs="staffs"
            :role="role"
            :dasar="dasar"
        ></Body>
    </div>
</template>

<script>
import Body from "./body";
import Header from "./headers";
export default {
    data() {
        return {
            role: null,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        };
    },
    props: {
        todos: Array,
        doing: Array,
        done: Array,
        staffs: Array,
        user: Object,
        dasar: Array,
    },
    components: {
        Body,
        Header,
    },
    mounted() {
        this.role = this.user.role;
        console.log("Component mounted.", this.user);
    },
};
</script>
<style lang="scss">
.dropdown-toggle::after {
    display: none;
}
.user-btn {
    z-index: 1;
    height: 36px;
}
.nav-menu .menu-user {
    margin-top: -30px !important;
}
.nav-menu {
    height: 44px;
    margin-right: -6vw;
}
.img-dropdown img {
    border-radius: 50%;
    background: #fff;
    padding: 2px;
    margin-right: 5px;
}
</style>
